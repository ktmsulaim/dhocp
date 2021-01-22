<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Announcements</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="$inertia.get($route('announcements.index'))"
                    type="primary"
                    >Back to Announcements</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <h4>{{ announcement.data.title }}</h4>
              <div>{{ announcement.data.body }}</div>

              <hr />
              <div class="mt-3">
                <h5>Message info</h5>
                <div class="mt-2">
                  <p>
                    <b class="form-control-label">Date:</b>
                    {{ announcement.data.created_at }}
                  </p>
                  <p>
                    <b class="form-control-label">Last Modified:</b>
                    {{ announcement.data.last_modified }}
                  </p>
                  <p>
                    <b class="form-control-label">Delivered to:</b>
                    {{ announcement.data.viewed.delivered }}
                  </p>
                  <p>
                    <b
                      @click="showReadBy"
                      style="cursor: pointer"
                      class="form-control-label"
                      >Read by:</b
                    >
                    {{ announcement.data.viewed.count }}
                  </p>
                </div>
              </div>

              <modal :show.sync="modals.readBy">
                <template slot="header">
                  <h5 class="modal-title" id="exampleModalLabel">Read by</h5>
                </template>
                <div class="scroller">
                  <div v-if="readByUsers.users && readByUsers.users.length > 0">
                    <ul class="list-group">
                      <li
                        v-for="data in readByUsers.users"
                        :key="data.user.id"
                        class="list-group-item"
                      >
                        <div class="d-flex align-items-center">
                          <div class="photo">
                            <img
                              :src="data.user.profile"
                              alt=""
                              class="rounded-circle profile"
                            />
                          </div>
                          <div class="name mx-3">
                            <p class="m-0">
                              <b class="form-control-label">{{
                                data.user.name
                              }}</b>
                              <small class="d-inline-block ml-3">{{
                                data.user.enroll_no
                              }}</small>
                            </p>
                            <p>
                              <b-icon icon="clock"></b-icon>
                              <span>{{ data.read_at_formatted }}</span>
                            </p>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <div v-else>
                    <p>No students!</p>
                  </div>
                </div>
                <template slot="footer">
                  <base-button type="secondary" @click="modals.readBy = false"
                    >Close</base-button
                  >
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
  layout: DashboardLayout,
  props: ["announcement"],
  data() {
    return {
      modals: {
        readBy: false,
      },
      readByUsers: {
        loading: true,
        users: [],
      },
    };
  },
  methods: {
    showReadBy() {
      if (!this.readByUsers.users || this.readByUsers.users.length == 0) {
        this.readByUsers.loading = true;
        axios
          .get(
            this.$route("announcements.readBy", {
              announcement: this.announcement.data.id,
            })
          )
          .then((resp) => {
            this.readByUsers.users = resp.data.data;
          })
          .catch((err) => console.error("Unable to load read by students!"))
          .finally(() => {
            this.readByUsers.loading = false;
          });
      }

      this.modals.readBy = true;
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "View announcement");
  },
};
</script>

<style scoped>
.scroller {
  max-height: 350px;
  overflow-y: scroll;
}

.profile {
  width: 50px;
  height: 50px;
}
</style>