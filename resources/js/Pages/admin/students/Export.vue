<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <h3>Export students</h3>
            </div>
            <div class="card-body">
              <form
                ref="form"
                :action="$route('export.students')"
                method="post"
              >
                <input v-model="token" type="hidden" name="_token" />
                <input
                  v-model="selectedModules"
                  type="hidden"
                  name="modules[]"
                />
                <p>
                  <b class="form-control-label">Export settings:</b>
                </p>
                <div class="my-4">
                  <h4>Modules</h4>
                  <div class="row">
                    <div class="col-md-6">
                      <div v-if="modules && modules.length > 0">
                        <base-checkbox
                          :multiple="true"
                          v-for="module in modules"
                          :key="module.id"
                          class="custom-control-alternative"
                        >
                          <span @click="addToItems(module.id)">{{
                            module.name
                          }}</span>
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

export default {
  layout: DashboardLayout,
  props: ["batches", "modules"],
  data() {
    return {
      selectedModules: [],
      students: null,
      loading: false,
      token: $('meta[name="csrf-token"]')[0].content,
    };
  },
  methods: {
    addToItems(moduleId) {
      if (moduleId) {
        this.selectedModules.push(moduleId);
      }
    },
    submitForm() {
      const data = {};

      if (this.selectedModules && this.selectedModules.length == 0) {
        return;
      }

      if (this.students) {
        this.loading = true;
        data.students = this.students;

        this.$refs.form.submit();

        this.loading = false;
      }
    },
  },
  computed: {
    finalModules() {
      if (this.selectedModules) {
        const items = this.selectedModules.map((item) => {
          if (item) {
            return item;
          }
        });
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Export students");
  },
};
</script>

<style>
</style>