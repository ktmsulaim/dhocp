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
                  <h3 class="mb-0">View student</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    icon="ni ni-caps-small"
                    @click="edit"
                    type="default"
                    >Edit</base-button
                  >
                  <base-button
                    @click="backToStudents"
                    type="primary"
                    icon="ni ni-bold-left"
                    >Back to students</base-button
                  >
                </div>
              </div>
            </div>
            <div class="container">
              <div class="row my-3">
                <div class="col col-md-4 text-center">
                  <img src="/img/user.png" alt="User icon" class="img-fluid" />
                  <div class="mt-2">
                    <h4>{{ student.data.name }}</h4>
                  </div>
                </div>
                <div class="col col-md-8">
                  <div class="table-responsive">
                    <table class="table border">
                      <tr>
                        <th width="100">Batch</th>
                        <td>{{ student.data.batch.name }}</td>
                      </tr>
                      <tr>
                        <th width="100">Enroll no</th>
                        <td>{{ student.data.enroll_no }}</td>
                      </tr>
                      <tr>
                        <th width="100">DOB</th>
                        <td>{{ student.data.dob_formatted }}</td>
                      </tr>
                      <tr>
                        <th width="100">Status</th>
                        <td>
                          <badge v-if="student.data.active == 1" type="success"
                            >Active</badge
                          >
                          <badge v-else type="danger">Suspended</badge>
                        </td>
                      </tr>
                    </table>
                  </div>
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
import DashboardLayout from "../../../layout/DashboardLayout";

export default {
  props: ["student"],
  layout: DashboardLayout,
  methods: {
    backToStudents() {
      this.$inertia.get(this.$route("students.index"));
    },
    edit() {
      if (this.student) {
        this.$inertia.get(
          this.$route("students.edit", { id: this.student.data.id })
        );
      }
    },
  },
};
</script>

<style>
</style>