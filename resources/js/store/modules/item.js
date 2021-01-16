const state = {
    itemValues: {},
    uploadedFiles:{}
};

const getters = {
   getItemValue(state) {
       return state.itemValues;
   },
   getUploadedFiles(state){
       return state.uploadedFiles;
   }
};

const actions = {

};

const mutations = {
    setItemValue(state, data){
        state.itemValues = data;
    },
    setItemValueById(state, data){
        state.itemValues[data.id] = data.value;
    },
    setUploadedFiles(sate, data) {
        state.uploadedFiles = data;
    },
    setUploadedFilesById(state, data) {
        state.uploadedFiles[data.id] = data.value;
    }
};

export default {
    state, getters, actions, mutations
}