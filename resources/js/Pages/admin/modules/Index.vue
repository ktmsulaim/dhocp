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
                  <h3 class="mb-0">All modules</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="addModule"
                    type="primary"
                    icon="ni ni-fat-add"
                    >Add a module</base-button
                  >
                </div>
              </div>
            </div>

            <div class="container my-3">
              <div v-if="modules && modules.length > 0" class="row">
                <div v-for="(mod, i) in modules" :key="i" class="col col-md-4">
                  <div class="card shadow my-3">
                    <div class="card-header">
                      <h3>{{ mod.name }}</h3>
                    </div>
                    <div class="py-3">
                      <div class="container">
                        <div class="row">
                          <div class="col">
                            <p>
                              <b>{{ mod.items_count }}</b> Items
                            </p>
                            <div class="text-center">
                              <base-button
                                size="sm"
                                type="default"
                                @click="editModal(mod.id)"
                                >Edit</base-button
                              >
                              <base-button
                                @click="viewModule(mod.id)"
                                size="sm"
                                type="primary"
                                >View</base-button
                              >
                              <base-button
                                @click="showDeleteModal(mod.id)"
                                size="sm"
                                type="danger"
                                >Delete</base-button
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="row">
                <div class="col">
                  <p class="text-muted">No modules found!</p>
                </div>
              </div>

              <!-- Modals -->
              <modal
                :show.sync="modals.form"
                body-classes="p-0"
                modal-classes="modal-dialog-centered modal-sm"
              >
                <card
                  type="secondary"
                  shadow
                  header-classes="bg-white pb-5"
                  body-classes="px-lg-5 py-lg-5"
                  class="border-0"
                >
                  <template>
                    <div class="text-center text-muted my-4">
                      <small v-if="editModule.status">Edit module</small>
                      <small v-else>Add a new module</small>
                    </div>
                    <form role="form">
                      <div>
                        <base-input alternative>
                          <input
                            type="text"
                            class="form-control form-control-alternative"
                            placeholder="Name"
                            v-model="form.name"
                          />
                          <p class="text-danger" v-if="form.errors.name">
                            <small>{{ form.errors.name }}</small>
                          </p>
                        </base-input>
                      </div>

                      <div>
                        <base-input alternative="">
                          <textarea
                            rows="4"
                            class="form-control form-control-alternative"
                            placeholder="Description"
                            v-model="form.description"
                          ></textarea>
                          <p class="text-danger" v-if="form.errors.description">
                            <small>{{ form.errors.description }}</small>
                          </p>
                        </base-input>
                      </div>

                      <div>
                        <base-input alternative>
                          <select
                            class="form-control form-control-alternative"
                            v-model="form.status"
                          >
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                          </select>
                          <p class="text-danger" v-if="form.errors.status">
                            <small>{{ form.errors.status }}</small>
                          </p>
                        </base-input>
                      </div>

                      <div class="text-center">
                        <base-button
                          type="secondary"
                          class="my-4 mr-2"
                          @click="closeModal"
                          >Cancel</base-button
                        >
                        <base-button
                          :loading="form.processing"
                          :disabled="form.processing"
                          type="primary"
                          class="my-4"
                          @click="submitForm"
                          >Submit</base-button
                        >
                      </div>
                    </form>
                  </template>
                </card>
              </modal>

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
                    This action will delete the selected module and all items
                    belong to it. Remember this action can't be undone!
                  </p>
                </div>

                <template slot="footer">
                  <base-button
                    @click="destroy"
                    :loading="deleteModule.loading"
                    :disabled="deleteModule.loading"
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
</template>

<script>
import DashboardLayout from "../../../layout/DashboardLayout";

export default {
  props: ["modules"],
  layout: DashboardLayout,
  data() {
    return {
      modals: {
        form: false,
        delete: false,
      },
      form: this.$inertia.form({
        name: null,
        description: null,
        status: 1,
      }),
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
      editModule: {
        status: false,
        id: null,
      },
      deleteModule: {
        loading: false,
        id: null,
      },
    };
  },
  methods: {
    viewModule(id) {
      if (id) {
        this.$inertia.get(this.$route("modules.show", { module: id }));
      }
    },
    addModule() {
      if (this.editModule.status) {
        this.editModule.status = false;
        this.editModule.id = null;
      }

      this.form.reset();

      this.modals.form = true;
    },
    editModal(id) {
      if (id) {
        this.editModule.status = true;
        this.editModule.id = id;
        this.modals.form = true;

        const modul = this.modules.find((mod) => mod.id == id);
        this.form.name = modul.name;
        this.form.description = modul.description;
        this.form.status = modul.status;
      }
    },
    isValid(key) {
      if (this.form[key] && this.form.errors[key] == null) {
        return true;
      } else {
        return false;
      }
    },
    closeModal() {
      this.modals.form = false;

      if (this.editModule.status) {
        this.editModule.status = false;
        this.editModule.id = null;
      }
    },
    createModule() {
      this.form.post(this.$route("modules.store"));
    },
    updateModule() {
      if (this.editModule.id) {
        this.form.patch(
          this.$route("modules.update", { module: this.editModule.id })
        );
      }
    },
    submitForm() {
      if (this.editModule.status) {
        this.updateModule();
      } else {
        this.createModule();
      }
    },
    showDeleteModal(id) {
      if (id) {
        this.deleteModule.id = id;
        this.modals.delete = true;
      }
    },
    closeDeleteModal() {
      this.deleteModule.id = null;
      this.modals.delete = false;
    },
    destroy() {
      if (this.deleteModule.id) {
        this.deleteModule.loading = true;
        this.$inertia.post(
          this.$route("modules.destroy", { module: this.deleteModule.id }),
          null,
          {
            onSuccess: (page) => {
              this.deleteModule.loading = false;
              this.deleteModule.id = null;
              this.modals.delete = false;
            },
          }
        );
      }
    },
  },
  watch: {
    form: {
      handler(value) {
        if (value.recentlySuccessful) {
          this.notification = {
            status: true,
            type: "success",
            head: "Success",
            message: "The request has been processed!",
          };
        } else {
          this.notification.status = false;
        }

        if (value.wasSuccessful == true) {
          this.form.reset();
          this.modals.form = false;

          if (this.editModule.status == true) {
            this.editModule.status = false;
            this.editModule.id = null;
          }

          value.wasSuccessful = false;
        }
      },
      deep: true,
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Modules");
  },
};
</script>

<style>
</style>