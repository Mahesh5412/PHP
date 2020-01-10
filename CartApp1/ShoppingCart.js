

import React, { Component } from "react";
import {
    View,
    Text,
    StyleSheet
} from "react-native";

import {createStackNavigator, createAppContainer} from 'react-navigation';
//import { createStackNavigator } from 'react-navigation'
import HomeScreen from './containers/HomeScreen';
import ElectronicsScreen from './containers/ElectronicsScreen';
import BooksScreen from './containers/BooksScreen';
import ShoppingCartIcon from './containers/ShoppingCartIcon';
import CartScreen from './containers/CartScreen';


const MainNavigator = createStackNavigator({
    Home: HomeScreen,
    Electronics: ElectronicsScreen,
    Books: BooksScreen,
    Cart: CartScreen

   }, 

{
    defaultNavigationOptions: {
            headerTitle: 'Shopping App',
            headerRight: (
                <ShoppingCartIcon />
            )
        }
    }
    )
   // alert('shopping cart');
    const ShoppingCart = createAppContainer(MainNavigator);
    
    export default ShoppingCart;
    
const styles = StyleSheet.create({
    container: {
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center'
    }
});