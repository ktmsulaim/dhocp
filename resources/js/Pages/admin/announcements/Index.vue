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
              <div v-if="announcements.data && announcements.data.length > 0">
                <div class="table-responsive">
                  <table class="table border">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(ann, i) in announcements.data" :key="ann.id">
                        <td>{{ i + 1 }}</td>
                        <td>{{ ann.title }}</td>
                        <td>
                          <badge v-if="ann.status == 1" type="success"
                            >Published</badge
                          >
                          <badge v-else type="danger">Draft</badge>
                        </td>
                        <td>{{ ann.created_at }}</td>
                        <td>
                          <base-button type="info" size="sm">
                            <b-icon icon="eye-fill"></b-icon>
                          </base-button>
                          <base-button type="primary" size="sm">
                            <b-icon icon="pencil-square"></b-icon>
                          </base-button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
    };
  },
  methods: {},
  created() {
    this.$store.dispatch("assignTitle", "Announcements");
  },
};
</script>

<style scoped>
</style>>
