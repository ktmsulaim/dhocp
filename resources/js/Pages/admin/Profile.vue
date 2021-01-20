<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <h3>Profile</h3>
            </div>
            <div class="card-body">
              <h4>Hello, {{ admin.name }}</h4>
              <p>{{ admin.email }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from "../../layout/DashboardLayout";

export default {
  props: ["admin"],
  layout: Layout,
  created() {
    this.$store.dispatch("assignTitle", "Profile");
  },
};
</script>

<style>
</style>