<template>
  <div class="container">
    <div v-if="type" class="row">
      <div class="table-responsive">
        <table class="table border">
          <div v-if="type == 'dropdown'">
            <div v-if="formOptions && formOptions.length > 0" class="my-2">
              <table class="table table-hover bordered">
                <tr>
                  <th class="text-center" colspan="4">Options</th>
                </tr>
                <tr>
                  <th width="50">#</th>
                  <th>Name</th>
                  <th>Value</th>
                  <th></th>
                </tr>
                <tr v-for="(opt, i) in formOptions" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>{{ opt.key }}</td>
                  <td>{{ opt.value }}</td>
                  <td width="50">
                    <base-button
                      type="danger"
                      size="sm"
                      @click="removeOptionFromForm(opt)"
                    >
                      <span class="ni ni-fat-remove"></span>
                    </base-button>
                  </td>
                </tr>
              </table>
            </div>
            <hr v-if="formOptions && formOptions.length > 0" />
            <div class="text-right p-2">
              <base-button size="md" @click="addRow">Add row</base-button>
            </div>
            <div v-if="tableOptions && tableOptions.length > 0">
              <tr
                @keypress.enter="addToForm(option)"
                v-for="(option, i) in tableOptions"
                :key="i"
              >
                <td width="50%">
                  <base-input
                    v-model="option.key"
                    placeholder="Name"
                  ></base-input>
                </td>
                <td width="50%">
                  <base-input
                    v-model="option.value"
                    placeholder="Value"
                  ></base-input>
                </td>
                <td width="100">
                  <base-button
                    @click="addToForm(option)"
                    size="sm"
                    type="success"
                    ><span class="ni ni-check-bold"></span
                  ></base-button>
                  <base-button @click="removeRow(i)" size="sm" type="danger"
                    ><span class="ni ni-fat-remove"></span
                  ></base-button>
                </td>
              </tr>
            </div>
            <div v-else-if="!tableOptions && !formOptions">
              <tr>
                <td class="text-right" colspan="4">
                  <p>
                    <span>No options were added!</span>
                  </p>
                </td>
              </tr>
            </div>
          </div>
        </table>
      </div>
    </div>
    <div class="row" v-else>
      <div class="col">
        <p class="text-muted">No type was entered!</p>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  props: ["type"],
  data() {
    return {
      width: "100% - 150px",
      form: {},
      tableOptions: [],
    };
  },
  computed: {
    ...mapGetters(["options"]),
    formOptions: {
      get() {
        return this.options;
      },
      set(value) {},
    },
  },
  methods: {
    ...mapActions(["addOptionToForm", "removeFromForm"]),
    addRow() {
      this.tableOptions.push({
        key: null,
        value: null,
      });
    },
    removeRow(index) {
      if (index > -1) {
        this.tableOptions.splice(index, 1);
      }
    },
    addToForm(obj) {
      this.addOptionToForm(obj);

      const index = this.tableOptions.indexOf(obj);
      this.tableOptions.splice(index, 1);
    },
    removeOptionFromForm(obj) {
      if (obj) {
        if (confirm("Are you sure?")) {
          this.removeFromForm(obj);
        }
      }
    },
  },
};
</script>

<style>
</style>