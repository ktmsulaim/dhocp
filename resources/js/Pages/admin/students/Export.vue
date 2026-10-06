<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header border-0 bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Export Students (Excel)</h3>
                </div>
                <div class="col-auto text-right">
                  <div class="btn-group" role="group">
                    <base-button
                      type="primary"
                      size="sm"
                      disabled
                    >
                      <i class="ni ni-bullet-list-67 mr-1"></i>
                      Export Data (Excel)
                    </base-button>
                    <base-button
                      type="secondary"
                      size="sm"
                      @click="$inertia.get($route('export.documents.index'))"
                    >
                      <i class="ni ni-archive-2 text-success mr-1"></i>
                      Export Documents (ZIP)
                    </base-button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form
                ref="form"
                :action="$route('export.students')"
                method="post"
                @submit.prevent="submitForm"
              >
                <input v-model="token" type="hidden" name="_token" />
                <input
                  :value="selectedModules.join(',')"
                  type="hidden"
                  name="modules[]"
                />
                <p>
                  <b class="form-control-label">Export settings:</b>
                </p>
                <p v-if="error" class="text-danger">{{ error }}</p>
                <div class="my-4">
                  <div class="row">
                    <div class="col-md-6">
                      <h4 class="mb-2">Modules</h4>
                      <div v-if="modules && modules.length > 0">
                        <base-checkbox
                          :checked="isAllSelected ? '1' : '0'"
                          class="custom-control-alternative font-weight-bold"
                          @input="toggleSelectAll"
                        >
                          Select All
                        </base-checkbox>
                        <hr class="my-2" />
                        <base-checkbox
                          v-for="module in modules"
                          :key="module.id"
                          :checked="selectedModules.includes(module.id) ? '1' : '0'"
                          class="custom-control-alternative"
                          @input="setModuleSelected(module.id, $event)"
                        >
                          {{ module.name }}
                        </base-checkbox>
                      </div>
                      <div v-else>
                        <p>No module found!</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="my-2">
                  <h4>Students</h4>

                  <div class="row">
                    <div class="form-group col-md-6">
                      <select
                        v-model="students"
                        name="students"
                        class="form-control form-control-alternative"
                      >
                        <option value="active">Active students</option>
                        <option value="all">All students</option>
                        <option
                          v-for="batch in batches"
                          :key="batch.id"
                          :value="batch.id"
                        >
                          {{ batch.name }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="mt-3">
                  <base-button
                    :loading="loading"
                    :disabled="loading"
                    @click="submitForm"
                    >Submit</base-button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/DashboardLayout";
import { downloadBlob } from "../../../components/downloadUtils";

export default {
  layout: DashboardLayout,
  props: ["batches", "modules"],
  data() {
    return {
      selectedModules: [],
      students: "active",
      loading: false,
      error: null,
      token: $('meta[name="csrf-token"]')[0].content,
    };
  },
  computed: {
    isAllSelected() {
      return (
        this.modules &&
        this.modules.length > 0 &&
        this.selectedModules.length === this.modules.length
      );
    },
  },
  methods: {
    toggleSelectAll(value) {
      const checked = value === "1" || value === true || value === 1;
      if (checked) {
        this.selectedModules = this.modules.map((module) => module.id);
      } else {
        this.selectedModules = [];
      }
    },
    setModuleSelected(moduleId, value) {
      const checked = value === "1" || value === true || value === 1;
      const index = this.selectedModules.indexOf(moduleId);

      if (checked && index === -1) {
        this.selectedModules.push(moduleId);
      } else if (!checked && index !== -1) {
        this.selectedModules.splice(index, 1);
      }
    },
    submitForm() {
      if (this.loading) {
        return;
      }

      this.error = null;

      if (!this.selectedModules.length) {
        this.error = "Please select at least one module.";
        return;
      }

      if (!this.students) {
        this.error = "Please select which students to export.";
        return;
      }

      this.loading = true;

      downloadBlob({
        url: this.$route("export.students"),
        data: {
          modules: [this.selectedModules.join(",")],
          students: this.students,
        },
        fallbackName: "Students.xlsx",
      })
        .catch(() => {
          this.error = "Export failed. Please try again.";
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Export students");
  },
};
</script>

<style>
</style>