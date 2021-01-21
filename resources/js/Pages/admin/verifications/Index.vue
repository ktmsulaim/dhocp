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
                  <h3 class="mb-0">Verifications</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="modals.form = true"
                    type="primary"
                    icon="ni ni-fat-add"
                    >Add a verification</base-button
                  >
                </div>
              </div>
            </div>
            <table
              v-if="verifications && verifications.length > 0"
              class="table tablesorter align-items-center table-flush"
            >
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Verified</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>

              <draggable
                class="list"
                :list="ordered"
                handle=".handle"
                group="Verifications"
                :element="'tbody'"
                dataIdAttr="data-id"
                @change="saveOrder"
                @start="drag = true"
                @end="drag = false"
              >
                <tr
                  class="handle"
                  v-for="(verification, i) in ordered"
                  :key="verification.id"
                >
                  <td :data-id="verification.id">{{ i + 1 }}</td>
                  <td class="budget">
                    {{ verification.name }}
                  </td>
                  <td>
                    {{ verification.verified }}
                  </td>
                  <td>
                    <badge v-if="verification.status == 1" type="success"
                      >Enabled</badge
                    >
                    <badge v-else type="danger">Disabled</badge>
                  </td>

                  <td class="text-right">
                    <base-dropdown class="dropdown" position="right">
                      <template>
                        <span
                          @click="showEditForm(verification.id)"
                          class="dropdown-item"
                          >Edit</span
                        >
                        <span
                          @click="showDeleteConfirmation(verification.id)"
                          class="dropdown-item"
                          >Delete</span
                        >
                      </template>
                    </base-dropdown>
                  </td>
                </tr>
              </draggable>
            </table>
            <div v-else class="p-3">
              <p>No verifications found!</p>
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
                    <small v-if="editVerification.status"
                      >Edit verification</small
                    >
                    <small v-else>Add a new verification</small>
                  </div>
                  <form role="form" @keypress.enter.prevent="submitForm">
                    <div class="form-group">
                      <label class="form-control-label">Name</label>
                      <input
                        type="text"
                        placeholder="Name"
                        v-model="form.name"
                        class="form-control form-control-alternative"
                      />
                    </div>

                    <div class="form-group">
                      <label class="form-control-label">Status</label>
                      <select
                        v-model="form.status"
                        class="form-control form-control-alternative"
                      >
                        <option value="1">Enabled</option>
                        <option value="0">Disabled</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="form-control-label">Order</label>
                      <input
                        type="number"
                        min="1"
                        placeholder="Order of verification"
                        v-model="form.order"
                        class="form-control form-control-alternative"
                      />
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
                  This action will delete the selected verification. Remember
                  this action can't be undone!
                </p>
              </div>

              <template slot="footer">
                <base-button
                  @click="destroy"
                  :loading="deleteVerification.loading"
                  :disabled="deleteVerification.loading"
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
import draggable from "vuedraggable";

export default {
  props: ["verifications"],
  layout: DashboardLayout,
  components: {
    draggable,
  },
  data() {
    return {
      modals: {
        form: false,
        delete: false,
      },
      form: this.$inertia.form({
        name: null,
        status: 1,
        order: null,
      }),
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
      editVerification: {
        id: null,
        status: false,
      },
      deleteVerification: {
        id: null,
        loading: false,
      },
      ordered: [],
    };
  },
  methods: {
    submitForm() {
      if (this.editVerification.status) {
        this.updateVerification();
      } else {
        this.addVerification();
      }
    },
    addVerification() {
      this.form.post("/admin/verifications", {
        onSuccess: (page) => {
          this.form.reset();
          this.modals.form = false;
        },
      });
    },
    showEditForm(id) {
      if (id) {
        this.editVerification.id = id;
        this.editVerification.status = true;
        this.modals.form = true;

        const verification = this.verifications.find(
          (verification) => verification.id == id
        );

        this.form.name = verification.name;
        this.form.status = verification.status;
        this.form.order = verification.order;
      }
    },
    closeModal() {
      this.modals.form = false;
      (this.editVerification.status = false), (this.editVerification.id = null);

      this.form.reset();
    },
    updateVerification() {
      if (this.editVerification.id) {
        this.form.put("/admin/verifications/" + this.editVerification.id, {
          onSuccess: (page) => {
            if (this.editVerification.status == true) {
              this.editVerification.status = false;
              this.editVerification.id = null;
            }
          },
        });
      }
    },
    showDeleteConfirmation(id) {
      if (id) {
        this.deleteVerification.id = id;
        this.modals.delete = true;
      }
    },
    destroy() {
      if (this.deleteVerification.id) {
        this.deleteVerification.loading = true;
        this.$inertia.post(
          "/admin/verifications/" + this.deleteVerification.id,
          null,
          {
            onSuccess: (page) => {
              this.deleteVerification.id = null;
              this.deleteVerification.loading = false;
              this.modals.delete = false;
            },
          }
        );
      }
    },
    closeDeleteModal() {
      this.modals.delete = false;
      this.deleteVerification.id = null;
    },
    isValid(key) {
      if (this.form[key] && this.form.errors[key] == null) {
        return true;
      } else {
        return false;
      }
    },
    saveOrder(e) {
      this.ordered.forEach((item, index) => {
        item.order = index + 1;
      });

      const items = this.ordered;
      axios
        .post("/admin/verifications/updateOrder", {
          verifications: items,
        })
        .then((resp) => {
          console.log("Order updated!");
          this.ordered = resp.data;
        })
        .catch((err) => {
          console.log("Unable to update the order!");
        });
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Verifications");

    if (this.verifications) {
      this.ordered = this.verifications;
    }
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
      },
      deep: true,
    },
    verifications: {
      handler(value) {
        this.ordered = value;
      },
    },
  },
};
</script>

<style scoped>
.handle {
  cursor: grab;
}
</style>