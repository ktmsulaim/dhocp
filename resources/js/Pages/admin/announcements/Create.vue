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
                  <h3 class="mb-0">Create announcement</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="$inertia.get($route('announcements.index'))"
                    type="primary"
                    icon="ni ni-fat-add"
                    >Back to Announcements</base-button
                  >
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
  layout: DashboardLayout,
  created() {
    this.$store.dispatch("assignTitle", "Create an announcement");
  },
};
</script>

<style>
</style>