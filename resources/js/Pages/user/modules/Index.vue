<template>
  <div>
    <base-header
      type="gradient-success"
      class="pb-6 pb-8 pt-5 pt-md-8"
    ></base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <h3>Data entry</h3>
            </div>
            <div class="card-body">
              <div class="row" v-if="modules && modules.length > 0">
                <div
                  class="col-md-4 col-sm-6"
                  v-for="(module, i) in modules"
                  :key="i"
                >
                  <div class="card my-2">
                    <div class="card-body">
                      <h4>{{ module.name }}</h4>
                    </div>
                    <div class="card-footer">
                      <base-button
                        @click="show(module.id)"
                        type="default"
                        size="sm"
                        >View</base-button
                      >
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="row">
                <div class="col">
                  <p class="text-muted">Stay tuned for modules!</p>
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
import DashboardLayout from "../../../layout/users/DashboardLayout";

export default {
  layout: DashboardLayout,
  props: ["modules"],
  methods: {
    show(id) {
      if (id) {
        this.$inertia.get(this.$route("user.modules.show", { module: id }));
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Data entry");
  },
};
</script>

<style>
</style>