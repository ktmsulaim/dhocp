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
                  <h3 class="mb-0">Announcements</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="$inertia.get($route('announcements.create'))"
                    type="primary"
                    icon="ni ni-fat-add"
                    >Add new</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <div v-if="items && items.length > 0">
                <div class="table-responsive">
                  <table class="table border">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Viewed</th>
                        <th>Date</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(ann, i) in items" :key="ann.id">
                        <td>{{ i + 1 }}</td>
                        <td>{{ ann.title }}</td>
                        <td>
                          <badge v-if="ann.status == 1" type="success">
                            <span class="hover" @click="updateStatus(ann.id)"
                              >Published</span
                            >
                          </badge>
                          <badge v-else type="danger">
                            <span class="hover" @click="updateStatus(ann.id)"
                              >Draft</span
                            >
                          </badge>
                        </td>
                        <td>
                          {{ ann.viewed.count }} / {{ ann.viewed.delivered }}
                        </td>
                        <td>{{ ann.created_at }}</td>
                        <td>
                          <base-button
                            @click="
                              $inertia.get(
                                $route('announcements.show', {
                                  announcement: ann.id,
                                })
                              )
                            "
                            type="info"
                            size="sm"
                          >
                            <b-icon icon="eye-fill"></b-icon>
                          </base-button>
                          <base-button
                            @click="
                              $inertia.get(
                                $route('announcements.edit', {
                                  announcement: ann.id,
                                })
                              )
                            "
                            type="primary"
                            size="sm"
                          >
                            <b-icon icon="pencil-square"></b-icon>
                          </base-button>
                          <base-button
                            @click="showDeleteModal(ann.id)"
                            type="danger"
                            size="sm"
                          >
                            <b-icon icon="dash-circle"></b-icon>
                          </base-button>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <modal
                    :show.sync="del.modal"
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
                        This action will delete the announcement. Remember this
                        action can't be undone!
                      </p>
                    </div>

                    <template slot="footer">
                      <base-button
                        @click="destroy"
                        :loading="del.loading"
                        :disabled="del.loading"
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
              <div v-else>
                <p>No data!</p>
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
  props: ["announcements"],
  layout: DashboardLayout,
  data() {
    return {
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
      del: {
        modal: false,
        id: null,
        loading: false,
      },
    };
  },
  computed: {
    items() {
      return this.announcements.data;
    },
  },
  methods: {
    showDeleteModal(id) {
      if (id) {
        this.del.id = id;
        this.del.modal = true;
      }
    },
    closeDeleteModal() {
      this.del.id = null;
      this.del.loading = false;
      this.del.modal = false;
    },
    destroy() {
      if (this.del.id) {
        this.del.loading = true;
        axios
          .delete(
            this.$route("announcements.destroy", { announcement: this.del.id })
          )
          .then((resp) => {})
          .catch((err) => console.error("Unable to delete the announcement!"))
          .finally(() => {
            const id = this.del.id;
            const ann = this.items.find((item) => item.id == id);
            this.items.splice(this.items.indexOf(ann), 1);

            this.del.id = null;
            this.del.loading = false;
            this.del.modal = false;
          });
      }
    },
    updateStatus(id) {
      if (id) {
        const ann = this.items.find((item) => item.id == id);
        const status = ann.status == 1 ? 0 : 1;
        axios
          .post(
            this.$route("announcements.updateStatus", { announcement: id }),
            {
              status,
            }
          )
          .then((resp) => {
            const newData = resp.data;
            this.items.splice(this.items.indexOf(ann), 1, newData);
          })
          .catch((err) => {
            console.error("Unable to update the status!");
          });
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Announcements");
  },
};
</script>

<style scoped>
.hover {
  cursor: pointer;
}
</style>>
