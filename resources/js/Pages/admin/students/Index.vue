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
                  <h3 class="mb-0">{{ pageTitle }}</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="addAStudent"
                    type="primary"
                    icon="ni ni-fat-add"
                    >Add a student</base-button
                  >
                </div>
              </div>
              <div class="row align-items-center mt-3">
                <div class="col d-flex justify-content-between">
                  <div>
                    <base-button
                      @click="$inertia.get($route('export.students.index'))"
                      type="info"
                      >Export data</base-button
                    >
                    <base-dropdown v-if="batches && batches.length > 0">
                      <base-button
                        slot="title"
                        type="secondary"
                        class="dropdown-toggle"
                      >
                        Select a batch
                      </base-button>
                      <inertia-link
                        :href="link + `?batch=${batch.id}`"
                        class="dropdown-item"
                        v-for="(batch, i) in batches"
                        :key="i"
                        >{{ batch.name }}</inertia-link
                      >
                      <div class="dropdown-divider"></div>
                      <inertia-link
                        :href="link + '?batch=all'"
                        class="dropdown-item"
                        >All students</inertia-link
                      >
                    </base-dropdown>
                  </div>
                  <base-input
                    placeholder="Search students"
                    v-model="search"
                  ></base-input>
                </div>
              </div>
            </div>
            <base-table
              v-if="resultQuery && resultQuery.length > 0"
              class="table align-items-center table-flush"
              :thead-classes="'thead-light'"
              tbody-classes="list"
              :data="resultQuery"
            >
              <template slot="columns">
                <th>#</th>
                <th>Name</th>
                <th>Enroll.No</th>
                <th>DOB</th>
                <th>Batch</th>
                <th>Status</th>
                <th></th>
              </template>

              <template slot-scope="{ row }">
                <td>{{ row.id }}</td>
                <td class="budget">
                  <inertia-link
                    :href="$route('students.show', { id: row.id })"
                    >{{ row.name }}</inertia-link
                  >
                </td>
                <td>
                  {{ row.enroll_no }}
                </td>
                <td>
                  {{ row.dob_formatted }}
                </td>

                <td>
                  {{ row.batch_name }}
                </td>

                <td>
                  <badge v-if="row.active == 1" type="success">Active</badge>
                  <badge v-else type="danger">Suspended</badge>
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
                      <inertia-link
                        :href="$route('students.edit', { id: row.id })"
                        class="dropdown-item"
                        >Edit</inertia-link
                      >
                      <span
                        @click="showDeleteModal(row.id)"
                        class="dropdown-item"
                        >Delete</span
                      >
                    </template>
                  </base-dropdown>
                </td>
              </template>
            </base-table>
            <div v-else-if="!selectedBatch" class="p-3">
              <p>Select a batch to see the students!</p>
            </div>
            <div v-else class="p-3">
              <p>No Students found!</p>
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
                  This action will delete the selected student. Remember this
                  action can't be undone!
                </p>
              </div>

              <template slot="footer">
                <base-button
                  @click="destroy"
                  :loading="deleteStudent.loading"
                  :disabled="deleteStudent.loading"
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
  layout: DashboardLayout,
  props: ["students", "batches", "link"],
  data() {
    return {
      modals: {
        delete: false,
      },
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
      deleteStudent: {
        id: null,
        loading: false,
      },
      search: null,
    };
  },
  methods: {
    destroy() {
      if (this.deleteStudent.id) {
        this.deleteStudent.loading = true;

        this.$inertia.post(
          this.$route("students.destroy", { id: this.deleteStudent.id }),
          null,
          {
            onSuccess: (page) => {
              this.deleteStudent.loading = false;
              this.closeDeleteModal();
            },
          }
        );
      }
    },
    showDeleteModal(id) {
      if (id) {
        this.deleteStudent.id = id;
        this.modals.delete = true;
      }
    },
    closeDeleteModal() {
      this.deleteStudent.id = null;
      this.modals.delete = false;
    },
    updateCtv() {
      this.$inertia.get(this.link + `?ctv=${this.ctv}`);
    },
    getQueryVariable(variable) {
      var query = window.location.search.substring(1);
      var vars = query.split("&");
      for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (decodeURIComponent(pair[0]) == variable) {
          return decodeURIComponent(pair[1]);
        }
      }
    },
    addAStudent() {
      this.$inertia.get(this.$route("students.create"));
    },
  },
  computed: {
    pageTitle() {
      const batchId = this.getQueryVariable("batch");

      if (batchId && batchId != "all") {
        const batch = this.batches.find((bat) => bat.id == batchId);

        return `Students in ${batch.name}`;
      } else {
        return "All students";
      }
    },
    resultQuery() {
      if (this.search) {
        return this.students.data.filter((item) => {
          return this.search
            .toLowerCase()
            .split(" ")
            .every((v) => item.name.toLowerCase().includes(v));
        });
      } else {
        return this.students.data;
      }
    },
    selectedBatch() {
      return this.getQueryVariable("batch");
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Students");
  },
};
</script>

<style>
</style>