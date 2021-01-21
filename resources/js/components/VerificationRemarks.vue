<template>
  <div>
    <base-button
      v-if="role == 'admin'"
      @click="modals.remarks = true"
      size="sm"
    >
      <b-icon icon="chat-right-text"></b-icon>
    </base-button>
    <small class="hover" @click="modals.remarks = true" v-else-if="remarks">
      <b-icon icon="chat-right-text"></b-icon>
      Remarks
    </small>
    <!-- Modals -->
    <modal :show.sync="modals.remarks">
      <base-alert
        class="mb-3"
        v-if="notification.status"
        :type="notification.type"
      >
        <strong>{{ notification.head }}!</strong>
        {{ notification.message }}
      </base-alert>
      <h6 slot="header" class="modal-title" id="modal-title-default">
        Remarks {{ verification.verification.name }}
      </h6>
      <div v-if="role == 'admin'" class="form-group">
        <label class="form-control-label">Remarks</label>
        <textarea
          class="form-control form-control-alternative"
          v-model="remarks"
        ></textarea>
      </div>
      <div v-else>
        <p v-if="remarks">{{ remarks }}</p>
        <p v-else>No remarks</p>
      </div>
      <template slot="footer">
        <base-button
          :loading="loading"
          :disabled="loading"
          @click="submit"
          type="primary"
          v-if="role == 'admin'"
          >Save changes</base-button
        >
        <base-button type="link" class="ml-auto" @click="modals.remarks = false"
          >Close
        </base-button>
      </template>
    </modal>
  </div>
</template>

<script>
export default {
  props: {
    role: {
      type: String,
      default: "admin",
      description: "Role of the user who currently use this component",
    },
    verification: {
      type: Object,
    },
    studentID: {
      type: [String, Number],
    },
  },
  data() {
    return {
      modals: {
        remarks: false,
      },
      remarks: null,
      loading: false,
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
    };
  },
  methods: {
    submit() {
      this.loading = true;
      axios
        .post(
          this.$route("student.verifications.updateRemarks", {
            id: this.studentID,
            verification: this.verification.verification_id,
          }),
          {
            remarks: this.remarks,
          }
        )
        .then((resp) => {
          this.showNotification(
            "success",
            "The request has been processed",
            "Sucess"
          );
        })
        .catch((err) =>
          this.showNotification(
            "danger",
            "Unable to update the remarks",
            "Error"
          )
        )
        .finally(() => {
          this.loading = false;
        });
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
    if (this.verification.remarks) {
      this.remarks = this.verification.remarks;
    }
  },
};
</script>

<style scoped>
.hover {
  cursor: pointer;
}
</style>