const operatorModule = {
  state: () => ({
    count: 2
  }),
  mutations: {
    increment(state) {
      // `state` is the local module state
      state.count++
    }
  },
  getters: {
    doubleCount(state) {
      return state.count * 5
    }
  },
  actions: {
    increment (context) {
      context.commit('increment')
    }
  }
}
export default operatorModule;

