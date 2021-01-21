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
      <div class="row">
        <div class="col">
          <div class="card mt-3">
            <div class="card-header">
              <h4>Verifications</h4>
            </div>
            <div class="card-body">
              <student-verifications :student="student"></student-verifications>
            </div>
          </div>
        </div>
      </div>
      <div class="row" v-if="modules && modules.length > 0">
        <div class="col">
          <div
            v-for="module in modules"
            :key="module.id"
            class="card shadow my-3"
          >
            <div class="card-header">
              <div class="row align-item-center">
                <div class="col">
                  <h4>{{ module.name }}</h4>
                </div>
                <div v-if="module.status == 0" class="col text-right">
                  <badge type="danger">Disabled</badge>
                </div>
                <div
                  v-else-if="module.hasDownloadableFiles == true"
                  class="col text-right"
                >
                  <base-button
                    :loading="download.loading"
                    :disabled="download.loading"
                    @click="downloadZip(module.id)"
                    icon="ni ni-archive-2"
                    >Download zip</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <module :module="module" :studentId="student.data.id" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/DashboardLayout";
import Module from "../../../components/Module";
import StudentVerifications from "./StudentVerifications";
export default {
  props: ["student", "modules"],
  data() {
    return {
      download: {
        loading: false,
      },
    };
  },
  layout: DashboardLayout,
  components: {
    Module,
    StudentVerifications,
  },
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
    downloadZip(moduleId) {
      if (moduleId) {
        this.download.loading = true;
        axios({
          method: "POST",
          url: this.$route("admin.modules.downloadFiles", {
            module: moduleId,
            id: this.student.data.id,
          }),
          responseType: "blob",
        })
          .then((resp) => {
            FileDownload(resp.data, resp.headers[1]);
            console.log("Zip archive saved!");
          })
          .catch((err) => {
            console.log("Error archiving files!");
          })
          .finally(() => {
            this.download.loading = false;
          });
      }
    },
  },
};
</script>

<style>
</style>