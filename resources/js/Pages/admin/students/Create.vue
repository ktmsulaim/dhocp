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
                  <h3 class="mb-0">Add a student</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="$inertia.get($route('import.students.index'))"
                    type="info"
                    >Import</base-button
                  >
                  <base-button
                    @click="backToStudents"
                    type="primary"
                    icon="ni ni-bold-left"
                    >Back to Students</base-button
                  >
                </div>
              </div>
            </div>
            <div class="my-3">
              <form role="form" @keypress.enter="submit">
                <div class="container">
                  <div class="row">
                    <div class="col">
                      <base-input
                        placeholder="Name of the student"
                        v-model="form.name"
                        :valid="form.name != null && form.errors.name == null"
                      >
                      </base-input>
                      <div v-if="form.errors.name" class="mt-2 text-danger">
                        <p class="small">{{ form.errors.name }}</p>
                      </div>
                    </div>
                    <div class="col">
                      <base-input
                        type="number"
                        min="0"
                        placeholder="Enroll No"
                        v-model="form.enroll_no"
                        :valid="
                          form.enroll_no != null &&
                          form.errors.enroll_no == null
                        "
                      ></base-input>
                      <div
                        v-if="form.errors.enroll_no"
                        class="mt-2 text-danger"
                      >
                        <p class="small">{{ form.errors.enroll_no }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <base-input
                        :valid="form['dob'] && form.errors['dob'] == null"
                        addon-left-icon="ni ni-calendar-grid-58"
                      >
                        <flat-pickr
                          placeholder="DOB"
                          slot-scope="{ focus, blur }"
                          @on-open="focus"
                          @on-close="blur"
                          :config="{ allowInput: true, dateFormat: 'd/m/Y' }"
                          class="form-control datepicker"
                          v-model="form.dob"
                        >
                        </flat-pickr>
                      </base-input>
                      <div v-if="form.errors.dob" class="mt-2 text-danger">
                        <p class="small">{{ form.errors.dob }}</p>
                      </div>
                    </div>
                    <div class="col">
                      <base-input
                        placeholder="Password"
                        v-model="form.dob_password"
                        :valid="
                          form.dob_password != null &&
                          form.errors.dob_password == null
                        "
                      >
                      </base-input>
                      <div
                        v-if="form.errors.dob_password"
                        class="mt-2 text-danger"
                      >
                        <p class="small">{{ form.errors.dob_password }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <select v-model="form.batch_id" class="form-control">
                        <option value="">Select a batch</option>
                        <option
                          v-for="(batch, i) in batches"
                          :key="i"
                          :value="batch.id"
                        >
                          {{ batch.name }}
                        </option>
                      </select>
                      <div v-if="form.errors.batch_id" class="mt-2 text-danger">
                        <p class="small">{{ form.errors.batch_id }}</p>
                      </div>
                    </div>
                    <div class="col">
                      <select v-model="form.active" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Suspended</option>
                      </select>
                    </div>
                    <div v-if="form.errors.active" class="mt-2 text-danger">
                      <p class="small">{{ form.errors.active }}</p>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col text-center">
                      <base-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        type="primary"
                        @click="submit"
                        >Submit</base-button
                      >
                    </div>
                  </div>
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
  props: ["batches"],
  data() {
    return {
      form: this.$inertia.form({
        batch_id: null,
        name: null,
        enroll_no: null,
        dob: null,
        dob_password: null,
        active: 1,
      }),
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
    };
  },
  computed: {},
  methods: {
    backToStudents() {
      this.$inertia.get(this.$route("students.index"));
    },
    submit() {
      this.form.post(this.$route("students.store"));
    },
  },
  watch: {
    form: {
      handler(value) {
        if (value.dob) {
          const date = value.dob;
          const parts = date.split("/");
          const joined = parts.join("");
          value.dob_password = joined;
        }

        if (value.recentlySuccessful) {
          this.notification = {
            status: true,
            type: "success",
            head: "Success",
            message: "The request has been processed!",
          };
        } else {
          this.notification.status = false;
        }

        if (value.wasSuccessful == true) {
          this.form.reset();
        }
      },
      deep: true,
    },
  },
};
</script>

<style>
</style>