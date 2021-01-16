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
                  <h3>Edit record</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="
                      $inertia.get(
                        $route('user.modules.show', { module: module.id })
                      )
                    "
                    >Back to {{ module.name }}</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <div>
                <div class="row" v-if="itemUsers && itemUsers.length > 0">
                  <div
                    v-for="item in itemUsers"
                    :key="item.id"
                    :class="getInputSize(item)"
                  >
                    <single-item
                      :item="item"
                      :oldValue="getItemValue(item)"
                    ></single-item>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <base-button
                :disabled="isFormDataEmpty"
                @click="submit"
                type="primary"
                >Submit</base-button
              >
              <base-button
                v-if="module.repeatable == 1"
                @click="showDeleteModal(itemGroupId)"
                type="danger"
                >Delete</base-button
              >
            </div>

            <modal
              :show.sync="modals.delete"
              gradient="danger"
              modal-classes="modal-danger modal-dialog-centered"
            >
              <h6
                slot="header"
                class="modal-title"
                id="modal-title-notification"
              >
                Your attention is required
              </h6>

              <div class="py-3 text-center">
                <i class="ni ni-bell-55 ni-3x"></i>
                <h4 class="heading mt-4">Are you sure?!</h4>
                <p>
                  This action will delete the selected record. Remember this
                  action can't be undone!
                </p>
              </div>

              <template slot="footer">
                <base-button
                  @click="destroy"
                  :loading="deleteItems.loading"
                  :disabled="deleteItems.loading"
                  loaderColor="#000000"
                  type="white"
                  >Ok, Got it</base-button
                >
                <base-button
                  type="link"
                  text-color="white"
                  class="ml-auto"
                  @click="closeDeleteModal"
                >
                  Close
                </base-button>
              </template>
            </modal>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/users/DashboardLayout";
import SingleItem from "../../../components/SingleItem";

import { mapGetters } from "vuex";

export default {
  props: ["module", "itemUsers"],
  layout: DashboardLayout,
  components: {
    SingleItem,
  },
  data() {
    return {
      form: {
        item_group_id: this.itemGroupId,
        module_id: this.module.id,
        data: {},
      },
      modals: {
        delete: false,
      },
      deleteItems: {
        id: null,
        loading: false,
      },
    };
  },
  computed: {
    ...mapGetters({
      itemValues: "getItemValue",
    }),
    itemGroupId() {
      if (this.module.repeatable == 1) {
        const item = this.itemUsers[0];
        return item.item_group_id;
      } else {
        return null;
      }
    },
    isFormDataEmpty() {
      return _.isEmpty(this.form.data);
    },
  },
  methods: {
    getItemValue(item) {
      if (item) {
        if (this.module.repeatable == 1) {
          const itemUser = this.itemUsers.find(
            (itemUser) => itemUser.item_id == item.id
          );
          return itemUser.value;
        } else {
          const itemUser = this.itemUsers.find(
            (itemUser) => itemUser.id == item.id
          );
          return itemUser.pivot.value;
        }
      }
    },
    getInputSize(item) {
      if (item.size) {
        const size = item.size;

        if (size == 1) {
          return "col-sm-12 col-md-12";
        } else if (size == 2) {
          return "col-sm-12 col-md-6";
        } else if (size == 3) {
          return "col-sm-12 col-md-4";
        } else if (size == 4) {
          return "col-sm-12 col-md-3";
        } else {
          return "";
        }
      }
    },
    submit() {
      if (this.itemGroupId) {
        this.form.item_group_id = this.itemGroupId;
      }

      if (this.itemValues) {
        this.form.data = this.itemValues;
      }

      if (this.form.data) {
        this.$inertia.post(this.$route("user.modules.updateItems"), this.form, {
          onSuccess: (page) => {},
        });
      }
    },
    showDeleteModal(item_group_id) {
      if (item_group_id) {
        this.deleteItems.id = item_group_id;
        this.modals.delete = true;
      }
    },
    closeDeleteModal() {
      this.deleteItems.id = null;
      this.modals.delete = false;
    },
    destroy() {
      if (this.deleteItems.id) {
        this.deleteItems.loading = true;

        this.$inertia.post(
          this.$route("user.modules.deleteRecord", {
            itemGroup: this.deleteItems.id,
          }),
          null,
          {
            onSuccess: (page) => {
              this.deleteItems.loading = false;
              this.closeDeleteModal();
            },
          }
        );
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Edit record | " + this.module.name);
  },
};
</script>

<style>
</style>