<template>
  <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    <div class="row">
      <div class="col-md-12">
        <h1 v-if="admin">Hello, {{ admin.name }}</h1>
      </div>
    </div>
  </base-header>
</template>

<script>
import Layout from "../../layout/DashboardLayout";

export default {
  layout: Layout,
  created() {
    this.$store.dispatch("assignTitle", "Profile");
  },
};
</script>

<style>
</style>