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
              <h3>Hello, {{ user.name }}</h3>
            </div>
            <div class="card-body">
              <div v-if="isQualified" class="mb-3">
                <base-alert type="success">
                  <strong>Congrats</strong> Your application has been accepted.
                  Please print the form for the further procedures!
                </base-alert>
              </div>
              <div v-else-if="invalidAlert.items > 0" class="mb-3">
                <base-alert type="danger">
                  <strong>Alert!</strong> You have
                  {{ invalidAlert.items }} invalid
                  <span
                    v-text="invalidAlert.items > 1 ? 'entries' : 'entry'"
                  ></span>
                  in {{ invalidAlert.modules }}
                  <span
                    v-text="invalidAlert.modules > 1 ? 'modules' : 'module'"
                  ></span
                  >.
                </base-alert>
              </div>
              <div class="mb-4">
                <h4>Data entry status</h4>
              </div>
              <div class="row" v-if="modules && modules.length > 0">
                <div
                  class="col-md-4 col-sm-6"
                  v-for="(mod, i) in modules"
                  :key="i"
                >
                  <div
                    class="card my-2"
                    :class="{ 'border-danger': mod.hasInvalid }"
                  >
                    <div class="card-body">
                      <h4 class="text-center">{{ mod.module.name }}</h4>
                      <hr />

                      <div v-if="mod.module.repeatable == 1">
                        <p class="small">
                          <b>Total: </b>
                          <span class="count">{{ mod.total_items }}</span>
                        </p>
                        <p class="small">
                          <b>Pending: </b>
                          <span class="count">{{ mod.total_pending }}</span>
                        </p>
                        <p class="small">
                          <b>Valid: </b>
                          <span class="count text-success">{{
                            mod.total_valid
                          }}</span>
                        </p>
                        <p class="small">
                          <b>Invalid: </b>
                          <span class="count text-danger">{{
                            mod.total_invalid
                          }}</span>
                        </p>
                      </div>

                      <!-- Office Use -->

                      <div v-else-if="mod.module.office_use == 1">
                        <p class="small">
                          <b>Total: </b>
                          <span class="count">{{ mod.total_items }}</span>
                        </p>
                        <p class="small">
                          <b>Valid: </b>
                          <span class="count text-success">{{
                            mod.total_valid
                          }}</span>
                        </p>
                        <p class="small">
                          <b>Invalid: </b>
                          <span class="count text-danger">{{
                            mod.total_invalid
                          }}</span>
                        </p>
                        <div class="row">
                          <div class="col mt-2">
                            <div class="">
                              <span class="completion mr-2"
                                >{{ mod.total_attended_perc }}%</span
                              >
                              <div>
                                <base-progress
                                  :type="'success'"
                                  :show-percentage="false"
                                  class="pt-0"
                                  :value="mod.total_attended_perc"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Normal -->

                      <div v-else>
                        <div class="row">
                          <div class="col">
                            <p class="small">
                              <b>Total: </b>
                              <span class="count">{{ mod.total_items }}</span>
                            </p>
                          </div>
                          <div class="col">
                            <p class="small">
                              <b>Attended: </b>
                              <span class="count">{{
                                mod.total_attended
                              }}</span>
                            </p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <p class="small">
                              <b>Pending: </b>
                              <span class="count">{{ mod.total_pending }}</span>
                            </p>
                          </div>
                          <div class="col">
                            <p class="small">
                              <b>Valid: </b>
                              <span class="text-success count">{{
                                mod.total_valid
                              }}</span>
                            </p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <p class="small">
                              <b>Invalid: </b>
                              <span class="text-danger count">{{
                                mod.total_invalid
                              }}</span>
                            </p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col mt-2">
                            <div class="">
                              <span class="completion mr-2"
                                >{{ mod.total_attended_perc }}%</span
                              >
                              <div>
                                <base-progress
                                  :type="'success'"
                                  :show-percentage="false"
                                  class="pt-0"
                                  :value="mod.total_attended_perc"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
import DashboardLayout from "../../layout/users/DashboardLayout";

export default {
  layout: DashboardLayout,
  props: ["user", "modules", "invalidAlert", "isQualified"],
  created() {
    this.$store.dispatch("assignTitle", "Dashboard");
  },
};
</script>

<style>
</style>