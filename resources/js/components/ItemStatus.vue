<template>
  <div>
    <badge :type="status.type">{{ status.name }}</badge>
  </div>
</template>

<script>
export default {
  props: ["item"],
  computed: {
    status() {
      if (this.item.pivot) {
        const valid = this.item.pivot.is_valid;
        let value = {};

        switch (valid) {
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
  },
};
</script>

<style>
</style>