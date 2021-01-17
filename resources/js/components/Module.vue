<template>
  <div v-if="loading">
    <div class="d-flex align-items-center justify-content-center">
      <loader size="50" color="#444444"></loader>
    </div>
  </div>
  <div v-else>
    <div v-if="module.repeatable == 1">
      <div class="container">
        <div class="row" v-if="itemGroups && itemGroups.length > 0">
          <div class="table-responsive">
            <table
              v-if="itemProps && itemProps.length > 0"
              class="table border"
            >
              <thead>
                <tr>
                  <th>#</th>
                  <th v-for="(item, itemIndex) in itemProps" :key="itemIndex">
                    {{ item.label }}
                  </th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(ig, igIndex) in itemGroups" :key="igIndex">
                  <td>{{ igIndex + 1 }}</td>
                  <td
                    v-for="(item2, item2Index) in itemProps"
                    :key="item2Index"
                  >
                    {{
                      getItemUser(item2, ig) ? getItemUser(item2, ig).value : ""
                    }}
                  </td>
                  <td width="100">
                    <item-status
                      role="admin"
                      type="itemGroup"
                      :item="ig"
                    ></item-status>
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
      <div v-if="module.office_use == 1">
        <div v-if="items && items.length > 0">
          <div class="row">
            <div
              v-for="item in items"
              :key="item.id"
              class="box"
              :class="getInputSize(item)"
            >
              <div class="d-flex align-items-center justify-content-between">
                <p class="form-control-label">
                  <b>{{ item.label }}</b>
                </p>
                <admin-item-edit
                  @update="fetchData"
                  :repeatable="module.repeatable"
                  :item="item"
                ></admin-item-edit>
              </div>
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
                <div v-else>
                  <span class="text-danger">No data!</span>
                </div>
              </div>
              <div v-if="item.pivot.value" class="mt-3">
                <item-status role="admin" :item="item">
                  <template v-if="item.type == 'file'" slot="docViewer">
                    <div class="d-flex align-items-center">
                      <span
                        style="cursor: pointer"
                        @click="downloadFile(item.pivot.id)"
                        class="mx-3"
                        >Download</span
                      >
                      <file-viewer
                        :type="isImage(item.pivot.value_info) ? 'image' : 'doc'"
                        :item="item"
                      />
                    </div>
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
              <div v-if="item.pivot.value" class="mt-3">
                <item-status role="admin" :item="item">
                  <template v-if="item.type == 'file'" slot="docViewer">
                    <div class="d-flex align-items-center">
                      <span
                        style="cursor: pointer"
                        @click="downloadFile(item.pivot.id)"
                        class="mx-3"
                        >Download</span
                      >
                      <file-viewer
                        :type="isImage(item.pivot.value_info) ? 'image' : 'doc'"
                        :item="item"
                      />
                    </div>
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
</template>

<script>
import ItemStatus from "./ItemStatus";
import FileViewer from "./FileViewer";
import Loader from "./Loader";
import AdminItemEdit from "./AdminItemEdit";

export default {
  props: ["module", "studentId"],
  components: {
    ItemStatus,
    FileViewer,
    Loader,
    AdminItemEdit,
  },
  data() {
    return {
      items: [],
      loading: true,
    };
  },
  computed: {
    itemGroups() {
      if (this.items) {
        return this.items.itemGroups;
      }
    },
    itemProps() {
      if (this.items) {
        return this.items.items;
      }
    },
  },
  methods: {
    fetchData() {
      if (this.studentId && this.module) {
        axios
          .get(
            this.$route("admin.students.items", {
              module: this.module.id,
              id: this.studentId,
            })
          )
          .then((resp) => {
            this.items = resp.data;
          })
          .catch((err) => {
            console.log(
              `Unable to load the items for module ${this.module.name}!`
            );
          })
          .finally(() => {
            this.loading = false;
          });
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
    getItemUser(item, itemGroup) {
      if (item && itemGroup) {
        return itemGroup.item_users.find(
          (itemUser) =>
            item.id == itemUser.item_id &&
            itemUser.item_group_id == itemGroup.id
        );
      }
    },
    downloadFile(itemId) {
      axios({
        method: "POST",
        url: this.$route("items.download", { id: itemId }),
        responseType: "blob",
      })
        .then((resp) => {
          FileDownload(resp.data, resp.headers[1]);
        })
        .catch((err) => {
          console.log("Unable to download the file!");
        });
    },
  },
  created() {
    this.fetchData();
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
  padding-bottom: 20px;
}
</style>