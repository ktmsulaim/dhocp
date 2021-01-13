<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row mb-3" v-if="notification.status">
                <div class="col">
                  <base-alert :type="notification.type">
                    <strong>{{ notification.head }}!</strong>
                    {{ notification.message }}
                  </base-alert>
                </div>
              </div>
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">{{ module.name }}</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    type="default"
                    icon="ni ni-fat-add"
                    @click="addItem"
                  >
                    Add an Item</base-button
                  >
                  <base-button
                    @click="backToModules"
                    type="primary"
                    icon="ni ni-bold-left"
                    >Back to modules</base-button
                  >
                </div>
              </div>
            </div>

            <div class="container my-3">
              <div class="row">
                <div class="col" v-if="items && items.length > 0">
                  <div class="row">
                    <div
                      v-for="(item, i) in items"
                      :key="i"
                      class="col col-md-6"
                    >
                      <div class="card">
                        <div class="card-header">
                          <h4>{{ item.label }}</h4>
                        </div>
                        <div class="card-body">
                          <p class="small text-muted">{{ item.description }}</p>
                          <div class="status">
                            <badge type="info">{{
                              item.type.toUpperCase()
                            }}</badge>
                            <badge type="primary">{{
                              item.required ? "Required" : "Not required"
                            }}</badge>
                          </div>
                        </div>
                        <div class="card-footer">
                          <base-button
                            @click="editItem(item.id)"
                            size="sm"
                            type="default"
                            >Edit</base-button
                          >
                          <base-button
                            @click="showDeleteModal(item.id)"
                            size="sm"
                            type="danger"
                            >Delete</base-button
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="col">
                  <p>No items found!</p>
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
                      This action will delete the selected item. Remember this
                      action can't be undone!
                    </p>
                  </div>

                  <template slot="footer">
                    <base-button
                      @click="destroy"
                      :loading="deleteItem.loading"
                      :disabled="deleteItem.loading"
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
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/DashboardLayout";

export default {
  layout: DashboardLayout,
  props: ["module", "items"],
  data() {
    return {
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
      modals: {
        delete: false,
      },
      deleteItem: {
        id: null,
        loading: false,
      },
    };
  },
  methods: {
    backToModules() {
      this.$inertia.get(this.$route("modules.index"));
    },
    addItem() {
      this.$inertia.get(
        this.$route("items.create", { module: this.module.id })
      );
    },
    editItem(id) {
      if (id) {
        this.$inertia.get(this.$route("items.edit", { item: id }));
      }
    },
    showDeleteModal(id) {
      if (id) {
        this.deleteItem.id = id;
        this.modals.delete = true;
      }
    },
    closeDeleteModal() {
      this.deleteItem.id = null;
      this.modals.delete = false;
    },
    destroy() {
      if (this.deleteItem.id) {
        this.deleteItem.loading = true;

        this.$inertia.post(
          this.$route("items.destroy", { item: this.deleteItem.id }),
          null,
          {
            onSuccess: (page) => {
              this.deleteItem.loading = false;
              this.deleteItem.id = null;
              this.modals.delete = false;
            },
          }
        );
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", this.module.name);
  },
};
</script>

<style>
</style>