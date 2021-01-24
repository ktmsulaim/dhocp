<template>
  <div>
    <div>
      <span
        style="cursor: pointer"
        v-b-tooltip.hover
        title="Edit"
        @click="modals.form = !modals.form"
        ><b-icon icon="pencil-square"></b-icon
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
            <h4>Edit content</h4>
          </div>
          <form @keypress.enter.prevent="submitForm" :key="item.id" role="form">
            <single-item :item="item" :oldValue="oldValue"></single-item>
            <div class="text-center">
              <base-button
                type="secondary"
                class="my-4 mr-2"
                @click="modals.form = false"
                >Cancel</base-button
              >
              <base-button
                :loading="loading"
                :disabled="loading"
                type="primary"
                class="my-4"
                @click="submitForm"
                >Submit</base-button
              >
            </div>
          </form>
        </template>
      </card>
    </modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import SingleItem from "./SingleItem";

export default {
  props: ["repeatable", "item"],
  components: {
    SingleItem,
  },
  data() {
    return {
      modals: {
        form: false,
      },
      form: {
        data: {},
        item_group_id: null,
      },
      loading: false,
    };
  },
  computed: {
    ...mapGetters({
      itemValues: "getItemValue",
    }),
    oldValue() {
      if (this.repeatable == 1) {
        return this.item.value;
      } else {
        return this.item.pivot.value;
      }
    },
    studentId() {
      if (this.repeatable == 1) {
        return this.item.user_id;
      } else {
        return this.item.pivot.user_id;
      }
    },
  },
  methods: {
    submitForm() {
      this.loading = true;

      if (this.itemValues) {
        this.form.data = this.itemValues;
      }

      if (this.repeatable == 1) {
        this.form.item_group_id = this.item.id;
      }

      this.$inertia.post(
        this.$route("admin.modules.updateItems", { id: this.studentId }),
        this.form,
        {
          preserveScroll: true,
          onSuccess: (page) => {
            this.loading = false;
            this.modals.form = false;

            this.$emit("update");
          },
        }
      );
    },
  },
};
</script>

<style>
</style>