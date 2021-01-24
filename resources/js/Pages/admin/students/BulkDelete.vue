<template>
  <div>
    <base-button
      @click="showDeleteModal"
      v-if="selected && selected.length > 0"
      type="danger"
      >Delete</base-button
    >

    <modal
      :show.sync="modals.delete"
      gradient="danger"
      modal-classes="modal-danger modal-dialog-centered"
    >
      <h6 slot="header" class="modal-title" id="modal-title-notification">
        Your attention is required
      </h6>

      <div class="py-3 text-center">
        <i class="ni ni-bell-55 ni-3x"></i>
        <h4 class="heading mt-4">Are you sure?!</h4>
        <p>
          This action will delete the selected students. Remember this action
          can't be undone!
        </p>
      </div>

      <template slot="footer">
        <base-button
          @click="destroy"
          :loading="loading"
          :disabled="loading"
          loaderColor="#000000"
          type="white"
          >Ok, Got it</base-button
        >
        <base-button
          type="link"
          text-color="white"
          class="ml-auto"
          @click="closeDeleteModal"
        >
          Close
        </base-button>
      </template>
    </modal>
  </div>
</template>

<script>
export default {
  props: ["selected"],
  data() {
    return {
      modals: {
        delete: false,
      },
      loading: false,
    };
  },
  methods: {
    showDeleteModal() {
      this.modals.delete = true;
    },
    closeDeleteModal() {
      this.modals.delete = false;
    },
    destroy() {
      if (this.selected && this.selected.length > 0) {
        this.loading = true;

        axios
          .post(this.$route("students.delete.bulk"), { ids: this.selected })
          .then((resp) => {
            this.modals.delete = false;
            this.$emit("updateStudents");
          })
          .catch((err) => console.error("Unable to delete the students"))
          .finally(() => {
            this.loading = false;
          });
      }
    },
  },
};
</script>

<style>
</style>