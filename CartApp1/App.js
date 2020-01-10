import React, {Component} from 'react';
import { StyleSheet, Text, View } from 'react-native';
import ShoppingCart from './ShoppingCart'
import { Provider } from 'react-redux'
import store from './store'

export default class App extends Component {
  render() {
   // alert('app');
    return (
      <Provider store={store}>
        <ShoppingCart />
      </Provider>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});


