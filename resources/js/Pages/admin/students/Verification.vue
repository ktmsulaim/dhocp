<template>
  <div>
    <base-button
      @click="modals.verify = !modals.verify"
      v-if="selected && selected.length > 0"
      >Verify</base-button
    >
    <modal :show.sync="modals.verify">
      <base-alert v-if="notification.status" :type="notification.type">
        <strong>{{ notification.head }}!</strong>
        {{ notification.message }}
      </base-alert>
      <template slot="header">
        <h5 class="modal-title" id="exampleModalLabel">Verification</h5>
      </template>
      <div>
        <p>{{ selected.length }} students selected</p>

        <div class="my-3">
          <loader v-if="loading" :size="35" :color="'#000000'" />
          <div v-else-if="verifications && verifications.length > 0">
            <div class="table-responsive">
              <table class="table border">
                <tr
                  v-for="verification in verifications"
                  :key="verification.id"
                >
                  <td>{{ verification.name }}</td>
                  <td>
                    <base-button
                      :loading="
                        approval.loading && approval.selected == verification.id
                      "
                      :disabled="
                        approval.loading && approval.selected == verification.id
                      "
                      @click="approve(verification.id)"
                      size="sm"
                      type="success"
                      >Approve</base-button
                    >
                    <base-button
                      :loading="
                        disapproval.loading &&
                        disapproval.selected == verification.id
                      "
                      :disabled="
                        disapproval.loading &&
                        disapproval.selected == verification.id
                      "
                      @click="disapprove(verification.id)"
                      size="sm"
                      type="danger"
                      >Disapprove</base-button
                    >
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div v-else>No verifications found!</div>
        </div>
      </div>
      <template slot="footer">
        <base-button type="secondary" @click="modals.verify = false"
          >Close</base-button
        >
        <!-- <base-button type="primary">Save changes</base-button> -->
      </template>
    </modal>
  </div>
</template>

<script>
import Loader from "../../../components/Loader";
export default {
  props: ["selected"],
  components: {
    Loader,
  },
  data() {
    return {
      modals: {
        verify: false,
      },
      approval: {
        loading: false,
        selected: null,
      },
      disapproval: {
        loading: false,
        selected: null,
      },
      loading: true,
      verifications: null,
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
    };
  },
  methods: {
    approve(id) {
      if (id) {
        this.approval.selected = id;
        this.approval.loading = true;

        axios
          .post(this.$route("verifications.approve", { verification: id }), {
            students: this.selected,
          })
          .then((resp) => {
            this.showNotification(
              "success",
              "The approval was successful!",
              "Success"
            );
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
          .post(this.$route("verifications.disapprove", { verification: id }), {
            students: this.selected,
          })
          .then((resp) => {
            this.showNotification(
              "success",
              "The disapproval was successful!",
              "Success"
            );
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
      .get("/api/admin/verifications?api_token=" + token)
      .then((resp) => {
        this.verifications = resp.data;
      })
      .catch((err) => console.error("Unable to load the verifications"))
      .finally(() => {
        this.loading = false;
      });
  },
};
</script>

<style>
</style>