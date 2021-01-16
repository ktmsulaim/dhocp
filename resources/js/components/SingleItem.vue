<template>
  <div>
    <div v-if="item">
      <div v-if="item.type == 'text'">
        <base-input
          alternative
          input-classes="form-control-alternative"
          :placeholder="item.placeholder"
          :label="item.label"
          :value="getItemValue"
          v-model="formData"
        >
        </base-input>
        <p v-if="item.required == 1 && !formData" class="text-danger small">
          This field is required
        </p>
      </div>
      <div v-else-if="item.type == 'number'">
        <base-input
          alternative
          input-classes="form-control-alternative"
          :placeholder="item.placeholder"
          :label="item.label"
          :value="getItemValue"
          v-model="formData"
          type="number"
          min="0"
        >
        </base-input>
        <p v-if="item.required == 1 && !formData" class="text-danger small">
          This field is required
        </p>
      </div>
      <base-input
        alternative
        :label="item.label"
        v-else-if="item.type == 'textarea'"
      >
        <textarea
          :placeholder="item.placeholder"
          rows="3"
          :text="getItemValue"
          class="form-control form-control-alternative"
          v-model="formData"
        ></textarea>
        <p v-if="item.required == 1 && !formData" class="text-danger small">
          This field is required
        </p>
      </base-input>
      <div v-else-if="item.type == 'dropdown'">
        <label class="form-control-label">{{ item.label }}</label>
        <div v-if="options && options.length > 0">
          <select
            class="form-control form-control-alternative"
            :value="getItemValue"
            v-model="formData"
          >
            <option
              v-for="(option, i) in options"
              :key="i"
              :value="option.value"
            >
              {{ option.key }}
            </option>
          </select>
          <p v-if="item.required == 1 && !formData" class="text-danger small">
            This field is required
          </p>
        </div>
        <div v-else>
          <p>No options!</p>
        </div>
      </div>
      <base-input alternative v-else-if="item.type == 'date'">
        <flat-pickr
          :placeholder="item.placeholder"
          slot-scope="{ focus, blur }"
          @on-open="focus"
          @on-close="blur"
          :config="{ allowInput: true, dateFormat: 'd/m/Y' }"
          class="form-control form-control-alternative datepicker"
          :value="getItemValue"
          v-model="formData"
        >
        </flat-pickr>
        <p v-if="item.required == 1 && !formData" class="text-danger small">
          This field is required
        </p>
      </base-input>
      <div class="my-2" v-else-if="item.type == 'checkbox'">
        <base-checkbox
          :value="getItemValue"
          v-model="formData"
          class="custom-control-alternative"
        >
          <span class="text-muted">{{ item.label }}</span>
        </base-checkbox>
        <p v-if="item.required == 1 && !formData" class="text-danger small">
          This field is required
        </p>
      </div>
      <div v-else-if="item.type == 'file'" class="file my-2">
        <file-upload
          :item="item"
          :uploadUrl="$route('user.fileupload')"
          :deleteUrl="$route('user.deleteUpload')"
        ></file-upload>
      </div>
    </div>
    <div v-else>
      <p>No input type detected!</p>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import FileUpload from "./FileUpload";

export default {
  props: ["item", "oldValue"],
  components: {
    FileUpload,
  },
  data() {
    return {
      form: {
        data: null,
      },
      formData: null,
    };
  },

  computed: {
    ...mapGetters({
      getItemValue: "getItemValue",
    }),
    options() {
      const additional = JSON.parse(this.item.additional);

      if (additional) {
        const obj = Object.values(additional);
        return obj;
      } else {
        return [];
      }
    },
  },
  created() {
    if (this.oldValue) {
      this.$store.commit("setItemValueById", {
        id: this.item.id,
        value: this.oldValue,
      });
      this.formData = this.oldValue;
    } else {
      this.$store.commit("setItemValueById", {
        id: this.item.id,
        value: null,
      });
    }
  },
  watch: {
    formData(newValue) {
      this.$store.commit("setItemValueById", {
        id: this.item.id,
        value: newValue,
      });
    },
  },
  destroyed() {
    this.$store.commit("setItemValue", {});
  },
};
</script>

<style>
</style>