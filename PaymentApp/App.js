import React, { Component } from 'react';
import {
  AppRegistry,
  StyleSheet,
  Text,
  View,
  TouchableHighlight,
  NativeModules,
  NativeEventEmitter
} from 'react-native';
import RazorpayCheckout from 'react-native-razorpay';


export default class App extends Component {

  render() {
    return (
      <View style={styles.container}>
      <TouchableHighlight onPress={() => {
        var options = {
          description: 'Credits towards consultation',
          image: 'https://i.imgur.com/3g7nmJC.png',
          currency: 'INR',
         // key: 'rzp_live_t8n7Clyba4i9Ov',
         key: 'rzp_test_niGQbHfgV0P9yG',
          amount: '100',
          external: {
            wallets: ['paytm']
          },
          name: 'foo',
          prefill: {
            email: 'raju@razorpay.com',
            contact: '9951591981',
            name: 'M Raju'
          },
          theme: {color: '#F37254'}
        }
        RazorpayCheckout.open(options).then((data) => {
          // handle successco
          console.log(data);
          alert(`Success: ${data.razorpay_payment_id}`);
        }).catch((error) => {
          // handle failure
          console.log(error);
          alert(`Error: ${error.code} | ${error.description}`);
        });
        RazorpayCheckout.onExternalWalletSelection(data => {
          console.log(data);
          alert(`External Wallet Selected: ${data.external_wallet} `);
        });
      }}>
      <Text style = {styles.text}>PAY</Text>
      </TouchableHighlight>
      </View>
    );
  }

}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5FCFF',
  },
  text: {
    fontSize: 20,
    fontWeight: 'bold',
  }
});
//pay_CmmtdjMztcq0oy
//pay_CmmvgSmAyFN2GD