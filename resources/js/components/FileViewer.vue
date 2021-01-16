<template>
  <div>
    <span class="hover" @click="modals.viewer = !modals.viewer">View</span>
    <modal :show.sync="modals.viewer">
      <template slot="header">
        <h5 class="modal-title" id="exampleModalLabel">{{ item.label }}</h5>
      </template>
      <div>
        <div v-if="type == 'image'">
          <div v-viewer="{ movable: false }">
            <img class="img-fluid" :src="url" />
          </div>
        </div>
        <div v-else>
          <pdf :src="url"></pdf>
        </div>
      </div>
      <template slot="footer">
        <base-button type="secondary" @click="modals.viewer = false"
          >Close</base-button
        >
      </template>
    </modal>
  </div>
</template>

<script>
import pdf from "vue-pdf";

export default {
  props: ["item", "type"],
  components: {
    pdf,
  },
  data() {
    return {
      modals: {
        viewer: false,
      },
    };
  },
  computed: {
    filename() {
      if (this.item && this.item.pivot) {
        const options = JSON.parse(this.item.pivot.value_info);

        if (options) {
          return options.name;
        }
      }
    },
    url() {
      if (this.item && this.item.pivot) {
        const options = JSON.parse(this.item.pivot.value_info);

        if (options) {
          return options.url;
        }
      }
    },
  },
};
</script>

<style>
.hover {
  cursor: pointer;
}
</style>