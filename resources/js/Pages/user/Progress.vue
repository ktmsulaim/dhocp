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
              <h3>Progress</h3>
            </div>
            <div class="card-body">
              <div v-if="verifications && verifications.length > 0">
                <div class="row">
                  <div class="col-md-8 mx-auto">
                    <!-- <light-timeline :items="items"></light-timeline> -->
                    <light-timeline :items="items">
                      <template slot="tag" slot-scope="{ item }">
                        {{ item.tag }}
                      </template>
                      <template slot="symbol" slot-scope="{ item }">
                        <div
                          class="item-circle"
                          :style="{ borderColor: item.color }"
                        ></div>
                      </template>
                      <template slot="content" slot-scope="{ item }">
                        <div>
                          {{ item.content }}
                        </div>
                        <verification-remarks
                          role="user"
                          :verification="item.obj"
                          :studentID="$page.props.user.id"
                        ></verification-remarks>
                      </template>
                    </light-timeline>
                  </div>
                </div>
              </div>
              <div v-else>
                <p>No verifications found!</p>
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
import VerificationRemarks from "../../components/VerificationRemarks";
export default {
  layout: DashboardLayout,
  props: ["verifications", "status"],
  components: {
    VerificationRemarks,
  },
  data() {
    return {
      show: true,
    };
  },
  computed: {
    items() {
      if (this.verifications) {
        return this.verifications.map((verification) => {
          const status = this.getStatusByVerification(verification.id);

          return {
            obj: status,
            tag: verification.name,
            color: status && status.status == 1 ? "#28a745" : "#dc3545",
            type: "circle",
            content: status && status.status == 1 ? status.updated_at : "",
          };
        });
      }
    },
  },
  methods: {
    getStatusByVerification(id) {
      if (id) {
        return this.status.data.find((veri) => veri.verification_id == id);
      }
    },
  },
};
</script>

<style>
@media only screen and (min-width: 768px) {
  .line-container .item-tag {
    width: 120px !important;
    left: -10rem !important;
  }
}

.line-container .line-item {
  margin-bottom: 15px;
}
</style>