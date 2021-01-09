<template>
  <layout>
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary shadow border-0">
          <div class="card-body px-lg-5 py-lg-5">
            <div class="text-center text-muted mb-4">
              <small>Sign in to continue</small>
            </div>
            <div class="errors" v-if="form.errors.enroll_no || form.errors.dob">
              <base-alert type="danger" v-if="form.errors.enroll_no">
                <span class="alert-inner--icon"
                  ><i class="ni ni-fat-remove"></i
                ></span>
                <span class="alert-inner--text"
                  ><strong>Error!</strong> {{ form.errors.enroll_no }}</span
                >
              </base-alert>
              <base-alert v-if="form.errors.dob" type="danger">
                <span class="alert-inner--icon"
                  ><i class="ni ni-fat-remove"></i
                ></span>
                <span class="alert-inner--text"
                  ><strong>Error!</strong> {{ form.errors.dob }}</span
                >
              </base-alert>
            </div>
            <form @keypress.enter="login" role="form" id="loginForm">
              <base-input
                class="input-group-alternative mb-3"
                placeholder="Enroll No"
                addon-left-icon="ni ni-email-83"
                v-model="form.enroll_no"
              >
              </base-input>

              <base-input
                class="input-group-alternative"
                placeholder="DOB"
                type="text"
                addon-left-icon="ni ni-lock-circle-open"
                v-model="form.dob"
              >
              </base-input>

              <base-checkbox
                v-model="form.remember_me"
                class="custom-control-alternative"
              >
                <span class="text-muted">Remember me</span>
              </base-checkbox>
              <div class="text-center">
                <base-button
                  :loading="form.processing"
                  @click.prevent="login"
                  type="primary"
                  class="my-4"
                  >Sign in</base-button
                >
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from "../../layout/AuthLayout";

export default {
  components: {
    Layout,
  },
  data() {
    return {
      form: this.$inertia.form({
        enroll_no: null,
        dob: null,
        remember_me: null,
      }),
    };
  },
  methods: {
    login() {
      this.form.clearErrors();
      this.form.post("/login");
    },
  },
};
</script>

<style>
</style>