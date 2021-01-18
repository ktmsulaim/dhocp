<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row mb-3" v-if="notification.status">
        <div class="col">
          <base-alert :type="notification.type">
            <strong>{{ notification.head }}!</strong>
            {{ notification.message }}
          </base-alert>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row align-items-center justify-content-between">
                <div class="col">
                  <h3>Import students</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="$inertia.get($route('students.index'))"
                    type="primary"
                    icon="ni ni-bold-left"
                    >Back to Students</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h4>Required columns</h4>
                  <div class="mt-3">
                    <ol>
                      <li>
                        <span class="form-control-label">Name</span>:
                        <code>Type: String</code>
                      </li>
                      <li>
                        <span class="form-control-label">Enroll no</span>:
                        <code>Type: Number</code>
                      </li>
                      <li>
                        <span class="form-control-label">DOB</span>:
                        <code>Type: Date</code>
                        <p>
                          <b>Important!</b> Date must be in the given format:
                          <code>dd/mm/yyyy</code> For E.g
                          <span>01/01/1997</span>
                        </p>
                      </li>
                      <li>
                        <span class="form-control-label">Active</span>:
                        <code>Type: Number</code>
                        <p>
                          <code>(1 => Active || 0 => Suspended)</code> <br />
                          If the student has completed the course successfully
                          give 1 else give 0
                        </p>
                      </li>
                    </ol>
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Batch</label>
                    <select
                      v-model="form.batch_id"
                      name="students"
                      class="form-control form-control-alternative"
                    >
                      <option
                        v-for="batch in batches"
                        :key="batch.id"
                        :value="batch.id"
                      >
                        {{ batch.name }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <b-form-file
                      v-model="form.file"
                      :state="Boolean(form.file)"
                      placeholder="Choose a file or drop it here..."
                      drop-placeholder="Drop file here..."
                    ></b-form-file>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <base-button
                type="primary"
                :loading="form.processing"
                :disabled="!form.batch_id || !form.file || form.processing"
                @click="importStudents"
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
  props: ["batches"],
  data() {
    return {
      form: this.$inertia.form({
        batch_id: null,
        file: null,
      }),
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
    };
  },
  methods: {
    importStudents() {
      if (this.form.batch_id && this.form.file) {
        this.form.post(this.$route("import.students"));
      }
    },
  },
  watch: {
    form: {
      handler(value) {
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