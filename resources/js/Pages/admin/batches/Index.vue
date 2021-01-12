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
                  <h3 class="mb-0">All Batches</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="modals.form = true"
                    type="primary"
                    icon="ni ni-fat-add"
                    >Add a batch</base-button
                  >
                </div>
              </div>
            </div>
            <base-table
              v-if="batches && batches.length > 0"
              class="table align-items-center table-flush"
              :thead-classes="'thead-light'"
              tbody-classes="list"
              :data="batches"
            >
              <template slot="columns">
                <th>#</th>
                <th>Name</th>
                <th>Start</th>
                <th>End</th>
                <th>Students</th>
                <th></th>
              </template>

              <template slot-scope="{ row }">
                <td>{{ row.id }}</td>
                <td class="budget">
                  {{ row.name }}
                </td>
                <td>
                  {{ row.start }}
                </td>
                <td>
                  {{ row.end }}
                </td>

                <td>
                  {{ row.users_count }}
                </td>

                <td class="text-right">
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
                      <span @click="showEditForm(row.id)" class="dropdown-item"
                        >Edit</span
                      >
                      <span
                        @click="showDeleteConfirmation(row.id)"
                        class="dropdown-item"
                        >Delete</span
                      >
                    </template>
                  </base-dropdown>
                </td>
              </template>
            </base-table>
            <div v-else class="p-3">
              <p>No Batches found!</p>
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
                  <base-alert v-if="form.hasErrors" type="danger">
                    <span class="alert-inner--icon"
                      ><i class="ni ni-fat-remove"></i
                    ></span>
                    <span class="alert-inner--text"
                      ><strong>Error!</strong> Pls fill all required field</span
                    >
                  </base-alert>
                  <div class="text-center text-muted my-4">
                    <small v-if="editBatch.status">Edit batch</small>
                    <small v-else>Add a new batch</small>
                  </div>
                  <form role="form" @keypress.enter="submitForm">
                    <base-input
                      alternative
                      class="mb-3"
                      placeholder="Name"
                      v-model="form.name"
                      :valid="isValid('name')"
                    >
                    </base-input>
                    <base-input
                      alternative
                      type="number"
                      min="1950"
                      placeholder="Start year"
                      v-model="form.start"
                      :valid="isValid('start')"
                    >
                    </base-input>
                    <base-input
                      alternative
                      type="number"
                      min="1950"
                      placeholder="End year"
                      v-model="form.end"
                      :valid="isValid('end')"
                    >
                    </base-input>
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
                  This action will delete the selected batch and all students
                  belong to it. Remember this action can't be undone!
                </p>
              </div>

              <template slot="footer">
                <base-button
                  @click="destroy"
                  :loading="deleteBatch.loading"
                  :disabled="deleteBatch.loading"
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
import DashboardLayout from "../../../layout/DashboardLayout";
export default {
  props: ["batches"],
  layout: DashboardLayout,
  data() {
    return {
      modals: {
        form: false,
        delete: false,
      },
      form: this.$inertia.form({
        name: null,
        start: null,
        end: null,
      }),
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
      editBatch: {
        id: null,
        status: false,
      },
      deleteBatch: {
        id: null,
        loading: false,
      },
    };
  },
  methods: {
    submitForm() {
      if (this.editBatch.status) {
        this.updateBatch();
      } else {
        this.addBatch();
      }
    },
    addBatch() {
      this.form.post("/admin/batches");
    },
    showEditForm(id) {
      if (id) {
        this.editBatch.id = id;
        this.editBatch.status = true;
        this.modals.form = true;

        const batch = this.batches.find((batch) => batch.id == id);

        this.form.name = batch.name;
        this.form.start = batch.start;
        this.form.end = batch.end;
      }
    },
    closeModal() {
      this.modals.form = false;
      (this.editBatch.status = false), (this.editBatch.id = null);

      this.form.reset();
    },
    updateBatch() {
      if (this.editBatch.id) {
        this.form.put("/admin/batches/" + this.editBatch.id);
      }
    },
    showDeleteConfirmation(id) {
      if (id) {
        this.deleteBatch.id = id;
        this.modals.delete = true;
      }
    },
    destroy() {
      if (this.deleteBatch.id) {
        this.deleteBatch.loading = true;
        this.$inertia.post("/admin/batches/" + this.deleteBatch.id, null, {
          onSuccess: (page) => {
            this.deleteBatch.id = null;
            this.deleteBatch.loading = false;
            this.modals.delete = false;
          },
        });
      }
    },
    closeDeleteModal() {
      this.modals.delete = false;
      this.deleteBatch.id = null;
    },
    isValid(key) {
      if (this.form[key] && this.form.errors[key] == null) {
        return true;
      } else {
        return false;
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Batches");
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

          if (this.editBatch.status == true) {
            this.editBatch.status = false;
            this.editBatch.id = null;
          }
        }
      },
      deep: true,
    },
  },
};
</script>

<style>
</style>