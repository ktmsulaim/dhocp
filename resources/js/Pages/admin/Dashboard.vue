<template>
  <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    <!-- Card stats -->
    <div class="row">
      <div class="col-xl-3 col-lg-6">
        <stats-card
          title="Total Batches"
          type="gradient-red"
          :sub-title="batches"
          icon="ni ni-bold"
          class="mb-4 mb-xl-0"
        >
        </stats-card>
      </div>
      <div class="col-xl-3 col-lg-6">
        <stats-card
          title="Students"
          type="gradient-orange"
          :sub-title="users"
          icon="ni ni-hat-3"
          class="mb-4 mb-xl-0"
        >
        </stats-card>
      </div>
      <div class="col-xl-3 col-lg-6">
        <stats-card
          title="Modules"
          type="gradient-green"
          :sub-title="modules"
          icon="ni ni-ungroup"
          class="mb-4 mb-xl-0"
        >
        </stats-card>
      </div>
      <div class="col-xl-3 col-lg-6">
        <stats-card
          title="Admins"
          type="gradient-info"
          :sub-title="admins"
          icon="ni ni-circle-08"
          class="mb-4 mb-xl-0"
        >
        </stats-card>
      </div>
    </div>
  </base-header>
</template>

<script>
import DashboardLayout from "../../layout/DashboardLayout";
export default {
  props: ["user", "batches", "users", "modules", "admins"],
  layout: DashboardLayout,
  methods: {},
  created() {
    this.$store.dispatch("assignTitle", "Dashboard");
  },
};
</script>

<style>
</style>