<template>
  <div class="d-flex align-items-center justify-content-between">
    <badge :type="status.type">{{ status.name }}</badge>
    <div v-if="role == 'admin'" class="d-flex align-items-center">
      <div class="mx-3">
        <base-button
          :disabled="buttonDisable"
          @click="updateStatus(2)"
          size="sm"
          type="success"
          icon="ni ni-check-bold"
          rounded
          v-if="currentStatus != 2"
          data-toggle="tooltip"
          data-placement="top"
          title="Validate"
        ></base-button>
        <base-button
          :disabled="buttonDisable"
          v-if="currentStatus != 0"
          @click="updateStatus(0)"
          size="sm"
          type="danger"
          icon="ni ni-fat-remove"
          rounded
          data-toggle="tooltip"
          data-placement="top"
          title="Invalidate"
        ></base-button>
      </div>
    </div>
    <div v-if="currentStatus == 0">
      <span
        data-toggle="tooltip"
        data-placement="top"
        title="Invalid message"
        class="size-20"
        preserve-scroll
        @click="modals.form = !modals.form"
        ><span class="ni ni-chat-round"></span
      ></span>
    </div>

    <!-- Modals -->
    <modal
      :show.sync="modals.form"
      body-classes="p-0"
      modal-classes="modal-dialog-centered modal-sm"
    >
      <card
        type="secondary"
        shadow
        header-classes="bg-white pb-5"
        body-classes="px-lg-5 py-lg-5"
        class="border-0"
      >
        <template>
          <div class="text-center text-muted my-4">
            <h4>Invalid message</h4>
          </div>
          <form v-if="role == 'admin'" role="form">
            <base-input>
              <textarea
                v-model="form.invalidMessage"
                class="form-control form-control-alternative"
                rows="5"
              ></textarea>
            </base-input>
            <div class="text-center">
              <base-button
                type="secondary"
                class="my-4 mr-2"
                @click="closeModal"
                >Cancel</base-button
              >
              <base-button
                :loading="form.processing"
                :disabled="form.processing"
                type="primary"
                class="my-4"
                @click="submitForm"
                >Submit</base-button
              >
            </div>
          </form>
          <p v-else>
            {{ invalidMessage }}
          </p>
        </template>
      </card>
    </modal>
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "item",
      description: "Item type to show the status",
    },
    item: {
      type: Object,
      description: "The item object",
    },
    role: {
      type: String,
      default: "user",
      description: "The role of the user",
    },
  },
  data() {
    return {
      buttonDisable: false,
      currentStatus: 1,
      modals: {
        form: false,
      },
      form: this.$inertia.form({
        invalidMessage: null,
      }),
    };
  },
  computed: {
    status() {
      if (this.item) {
        let value = {};

        switch (this.currentStatus) {
          case 0:
            value = {
              type: "danger",
              name: "Invalid",
            };
            break;
          case 1:
            value = {
              type: "default",
              name: "Pending",
            };
            break;
          case 2:
            value = {
              type: "success",
              name: "Valid",
            };
            break;

          default:
            value = {
              type: "default",
              name: "Unknown",
            };
            break;
        }

        return value;
      } else {
        return {};
      }
    },
    invalidMessage() {
      return this.form.invalidMessage;
    },
    itemId() {
      if (this.type == "item") {
        return this.item.pivot.id;
      } else {
        return this.item.id;
      }
    },
  },
  methods: {
    updateStatus(status) {
      let updateStatusLink = null;

      if (this.type == "item") {
        updateStatusLink = "students.items.udpateStatus";
      } else {
        updateStatusLink = "students.itemGroups.udpateStatus";
      }

      this.$inertia.post(
        this.$route(updateStatusLink, { id: this.itemId }),
        {
          status,
        },
        {
          preserveScroll: true,
          onBefore: (visit) => {
            this.buttonDisable = true;
          },
          onSuccess: (page) => {
            this.buttonDisable = false;

            this.currentStatus = status;
          },
        }
      );
    },
    closeModal() {
      this.modals.form = false;
    },
    submitForm() {
      let updateMessageLink = null;

      if (this.type == "item") {
        updateMessageLink = "students.items.udpateInvalidMessage";
      } else {
        updateMessageLink = "students.itemGroups.udpateInvalidMessage";
      }

      this.form.post(
        this.$route(updateMessageLink, {
          id: this.itemId,
        }),
        {
          preserveScroll: true,
          onSuccess: (page) => {
            this.modals.form = false;
          },
        }
      );
    },
  },
  created() {
    $('[data-toggle="tooltip"]').tooltip();

    if (this.type == "item") {
      this.currentStatus = this.item.pivot.is_valid;
      this.form.invalidMessage = this.item.pivot.valid_message;
    } else {
      this.currentStatus = this.item.is_valid;
      this.form.invalidMessage = this.item.valid_message;
    }
  },
};
</script>

<style>
.size-20 {
  font-size: 20px;
}
</style>