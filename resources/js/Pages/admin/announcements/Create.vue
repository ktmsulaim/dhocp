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
                    >Back to Announcements</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label class="form-control-label">Title</label>
                <input
                  type="text"
                  class="form-control form-control-alternative"
                  v-model="form.title"
                />
                <p class="text-danger mt-3" v-if="form.errors.title">
                  {{ form.errors.title }}
                </p>
              </div>
              <div class="form-group">
                <label class="form-control-label">Body</label>
                <textarea
                  class="form-control form-control-alternative"
                  cols="30"
                  rows="10"
                  v-model="form.body"
                ></textarea>
                <p class="text-danger mt-3" v-if="form.errors.body">
                  {{ form.errors.body }}
                </p>
              </div>
            </div>
            <div class="card-footer">
              <base-button
                type="primary"
                :loading="form.processing"
                :disabled="form.processing"
                @click="submit"
                >Submit</base-button
              >
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
  data() {
    return {
      form: this.$inertia.form({
        title: null,
        body: null,
        status: 1,
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(this.$route("announcements.store"));
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Create an announcement");
  },
};
</script>

<style>
</style>