<template>
  <div>
    <base-alert
      class="mb-3"
      v-if="notification.status"
      :type="notification.type"
    >
      <strong>{{ notification.head }}!</strong>
      {{ notification.message }}
    </base-alert>
    <b-overlay v-if="loading" :show="loading" rounded="sm"></b-overlay>
    <div v-else-if="verifications && verifications.length > 0">
      <div class="table-responsive">
        <table class="table border">
          <tr v-for="verification in items" :key="verification.id">
            <th width="55%">{{ verification.name }}</th>
            <td width="25%">
              <div v-if="verification.status == 1">
                <badge type="success">Verified</badge>
                <span class="mx-2">{{ verification.updated_at }}</span>
              </div>
              <badge v-else type="danger">Not verified</badge>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <base-button
                  :loading="
                    approval.loading && approval.selected == verification.id
                  "
                  :disabled="
                    approval.loading && approval.selected == verification.id
                  "
                  type="success"
                  size="sm"
                  @click="approve(verification.id)"
                >
                  <b-icon icon="check2-circle"></b-icon>
                </base-button>
                <base-button
                  :loading="
                    disapproval.loading &&
                    disapproval.selected == verification.id
                  "
                  :disabled="
                    disapproval.loading &&
                    disapproval.selected == verification.id
                  "
                  type="danger"
                  size="sm"
                  @click="disapprove(verification.id)"
                >
                  <b-icon icon="dash-circle"></b-icon>
                </base-button>
                <verification-remarks
                  :verification="getStatusByVerification(verification.id)"
                  :studentID="student.data.id"
                ></verification-remarks>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div v-else>
      <p>No data!</p>
    </div>
  </div>
</template>

<script>
import VerificationRemarks from "../../../components/VerificationRemarks";

export default {
  props: ["student"],
  components: {
    VerificationRemarks,
  },
  data() {
    return {
      loading: true,
      verifications: null,
      status: null,
      approval: {
        loading: false,
        selected: null,
      },
      disapproval: {
        loading: false,
        selected: null,
      },
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
    };
  },
  computed: {
    items() {
      if (this.verifications && this.status) {
        return this.verifications.map((verification) => {
          const status = this.getStatusByVerification(verification.id);
          return {
            id: verification.id,
            name: verification.name,
            status: status ? status.status : null,
            remarks: status ? status.remarks : null,
            updated_at: status ? status.updated_at : null,
          };
        });
      }
    },
  },
  methods: {
    getStatusByVerification(id) {
      if (id) {
        return this.status.find((veri) => veri.verification_id == id);
      }
    },
    approve(id) {
      if (id) {
        this.approval.selected = id;
        this.approval.loading = true;

        axios
          .post(
            this.$route("student.verifications.approve", {
              id: this.student.data.id,
              verification: id,
            }),
            {
              students: this.selected,
            }
          )
          .then((resp) => {
            this.updateList(id, resp.data.data);
          })
          .catch((err) => {
            this.showNotification(
              "danger",
              "Unable to process the request!",
              "Error"
            );
          })
          .finally(() => {
            this.approval.loading = false;
          });
      }
    },
    disapprove(id) {
      if (id) {
        this.disapproval.loading = true;
        this.disapproval.selected = id;
        axios
          .post(
            this.$route("student.verifications.disapprove", {
              id: this.student.data.id,
              verification: id,
            }),
            {
              students: this.selected,
            }
          )
          .then((resp) => {
            this.updateList(id, resp.data.data);
          })
          .catch((err) => {
            this.showNotification(
              "danger",
              "Unable to process the request!",
              "Error"
            );
          })
          .finally(() => {
            this.disapproval.loading = false;
          });
      }
    },
    updateList(id, newObj) {
      if (id) {
        const index = this.items.findIndex((item) => item.id == id);
        this.items.splice(index, 1, {
          id: newObj.verification.id,
          name: newObj.verification.name,
          status: newObj.status,
          remarks: newObj.remarks,
          updated_at: newObj.updated_at,
        });
      }
    },
    showNotification(type, message, head) {
      this.notification = {
        type,
        message,
        head,
        status: true,
      };
      const notification = this.notification;
      setTimeout(
        function () {
          // console.log(notification);
          notification.status = false;
        },
        3000,
        notification
      );
    },
  },
  created() {
    const token = this.$page.props.admin.api_token;

    axios
      .get(
        `/api/admin/users/${this.student.data.id}/verifications?api_token=${token}`
      )
      .then((resp) => {
        this.verifications = resp.data.verifications;
        this.status = resp.data.status;
      })
      .catch((err) => {
        console.error("Unable to load student verifications");
      })
      .finally(() => {
        this.loading = false;
      });
  },
};
</script>

<style>
</style>