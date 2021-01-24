<template>
  <div>
    <base-header
      type="gradient-success"
      class="pb-6 pb-8 pt-5 pt-md-8"
    ></base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3>{{ module.name }}</h3>
                </div>
                <div class="col text-right">
                  <base-button v-if="module.repeatable == 1" @click="addRecord"
                    >Add record</base-button
                  >
                  <base-button
                    v-else-if="module.office_use == 0"
                    @click="$inertia.get(`/modules/${module.id}/editRecord/`)"
                    >Edit record</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <div v-if="module.repeatable == 1" class="repeatable">
                <div class="container">
                  <div class="row" v-if="itemGroups && itemGroups.length > 0">
                    <div class="table-responsive">
                      <table
                        v-if="module.items && module.items.length > 0"
                        class="table border"
                      >
                        <thead>
                          <tr>
                            <th>#</th>
                            <th
                              v-for="(item, itemIndex) in module.items"
                              :key="itemIndex"
                            >
                              {{ item.label }}
                            </th>
                            <th>Status</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(ig, igIndex) in itemGroups"
                            :key="igIndex"
                          >
                            <td>{{ igIndex + 1 }}</td>
                            <td
                              v-for="(item2, item2Index) in module.items"
                              :key="item2Index"
                            >
                              {{
                                getItemUser(item2, ig)
                                  ? getItemUser(item2, ig).value
                                  : ""
                              }}
                            </td>
                            <td>
                              <item-status
                                role="user"
                                type="itemGroup"
                                :item="ig"
                              ></item-status>
                            </td>
                            <td width="80">
                              <base-dropdown class="dropdown" position="right">
                                <a
                                  slot="title"
                                  class="btn btn-sm btn-icon-only text-light"
                                  role="button"
                                  data-toggle="dropdown"
                                  aria-haspopup="true"
                                  aria-expanded="false"
                                >
                                  <i class="ni ni-bold-down"></i>
                                </a>

                                <template>
                                  <span
                                    @click="editRecord(ig.id)"
                                    class="dropdown-item"
                                    >Edit</span
                                  >
                                </template>
                              </base-dropdown>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div v-else class="row">
                    <div class="col">
                      <p>No data!</p>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else>
                <div v-if="items && items.length > 0">
                  <div class="row">
                    <div
                      v-for="item in items"
                      :key="item.id"
                      class="box"
                      :class="getInputSize(item)"
                    >
                      <p class="form-control-label">
                        <b>{{ item.label }}</b>
                      </p>
                      <div class="my-2">
                        <div v-if="item.pivot.value">
                          <span v-if="item.type == 'dropdown'">
                            {{ getOptionName(item.pivot.value, item) }}
                          </span>
                          <span v-else-if="item.type == 'checkbox'">
                            {{ item.pivot.value == 1 ? "Yes" : "No" }}
                          </span>
                          <div v-else-if="item.type == 'file'">
                            <div v-if="isImage(item.pivot.value_info)">
                              <img width="150" :src="item.pivot.value" alt="" />
                            </div>
                            <div v-else>
                              <div class="my-2">
                                <img width="150" src="/img/file.png" alt="" />
                              </div>
                            </div>
                          </div>
                          <span v-else>
                            {{ item.pivot.value }}
                          </span>
                        </div>
                        <span v-else class="text-danger">No data</span>
                      </div>
                      <div v-if="item.pivot.value" class="mt-2">
                        <item-status role="user" :item="item">
                          <template v-if="item.type == 'file'" slot="docViewer">
                            <file-viewer
                              :type="
                                isImage(item.pivot.value_info) ? 'image' : 'doc'
                              "
                              :item="item"
                            />
                          </template>
                        </item-status>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <p>No data!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/users/DashboardLayout";
import SingleItem from "../../../components/SingleItem";
import ItemStatus from "../../../components/ItemStatus";
import FileViewer from "../../../components/FileViewer";

export default {
  layout: DashboardLayout,
  props: ["module", "itemGroups", "items"],
  components: {
    SingleItem,
    ItemStatus,
    FileViewer,
  },
  data() {
    return {
      form: {
        data: {},
      },
    };
  },
  methods: {
    getItemUser(item, itemGroup) {
      if (item && itemGroup) {
        return itemGroup.item_users.find(
          (itemUser) =>
            item.id == itemUser.item_id &&
            itemUser.item_group_id == itemGroup.id
        );
      }
    },
    getInputSize(item) {
      if (item && item.size) {
        const size = item.size;

        if (size == 1) {
          return "cols-sm-12 col-md-12";
        } else if (size == 2) {
          return "cols-sm-12 col-md-6";
        } else if (size == 3) {
          return "cols-sm-12 col-md-4";
        } else if (size == 4) {
          return "cols-sm-12 col-md-3";
        } else {
          return "";
        }
      } else {
        return "col";
      }
    },
    addRecord() {
      this.$inertia.get(
        this.$route("user.modules.addRecord", { module: this.module.id })
      );
    },
    editRecord(itemGroupId) {
      if (itemGroupId) {
        this.$inertia.get(
          this.$route("user.modules.editRecord", {
            module: this.module.id,
            itemGroupId,
          })
        );
      }
    },
    getOptionName(value, item) {
      if (item.additional) {
        const options = Object.values(JSON.parse(item.additional));
        const option = options.find((option) => option.value == value);
        return option.key;
      }
    },
    isImage(info) {
      if (!info) return false;

      if (info) {
        const values = JSON.parse(info);

        if (!values) return false;

        if (values.type.includes("image")) return true;

        return false;
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", this.module.name);
  },
};
</script>

<style>
@media (max-width: 568px) {
  .box {
    padding: 15px;
    margin: 10px 0;
    border: 1px solid #f5f5f5;
  }
}

.box {
  margin-bottom: 20px;
  border-bottom: 2px solid #f1f1f1;
  padding-bottom: 10px;
}
</style>