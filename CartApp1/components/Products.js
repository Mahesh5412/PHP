
import React, { Component } from "react";
import {
    AppRegistry, StyleSheet, FlatList, Text, View, Alert, ActivityIndicator, Platform,
    Button,
    
} from "react-native";

 class Products extends Component {


    

    renderProducts = (products) => {

        console.log(products)
        return products.map((item, index) => {
            return (
                <View key={index} style={{ padding: 20 }}>
               
                    <Button onPress={() => this.props.onPress(item)} title={item.RestaurantName } />
                    {/* <Button onPress={() => alert("Add to cart")} title={item.name }/> */}
                </View>
            )
        })
        
    }



    render() {
        return (
            <View style={styles.container}>
             <Text>Product</Text>
                {this.renderProducts(this.props.products)}
            </View>
        );
    }
}
export default Products;


// constructor(props)
// {

//   super(props);

//   this.state = { 
//   isLoading: true
// }
// }

//   componentDidMount() {
  
//       return fetch('http://10.10.0.2/React-Native/restaurantList.php')
//         .then((response) => response.json())
//         .then((responseJson) => {
//           this.setState({
//             isLoading: false,
//             dataSource: responseJson
//           }, function() {
//             // In this block you can do something with new state.
//           });
//         })
//         .catch((error) => {
//           console.error(error);
//         });
//     }


// FlatListItemSeparator = () => {
//    return (
//      <View
//        style={{
//          height: 1,
//          width: "100%",
//          backgroundColor: "#607D8B",
//        }}
//      />
//    );
//  };

 

//   render() {

//       if (this.state.isLoading) {
//           return (
//             <View style={{flex: 1, paddingTop: 20}}>
//               <ActivityIndicator />
//             </View>
//           );
//         }

//       return (
//           <View style={styles.container}>
//           {/* <View>
//           {/* display product details from Products class in ElectronicsScreen *
//               <Products products={electronics} onPress={this.props.addItemToCart} />
//           </View> */}

//           <View style={styles.MainContainer}>
//         {/* <Products products={electronics} onPress={this.props.addItemToCart} /> */}


//      <FlatList
     
//         data={ this.state.dataSource }
        
//         ItemSeparatorComponent = {this.FlatListItemSeparator}
//         renderItem={({item}) => <Text style={styles.FlatListItemStyle}> {item.RestaurantName} </Text>}

//       //   renderItem={({item}) => <Text style={styles.FlatListItemStyle} onPress={this.props.item.addItemToCart} >  </Text>}

//         keyExtractor={(item, index) => index}
        
//        />
//         <Products products={electronics} onPress={this.props.addItemToCart} />
  
//   </View>
// </View>
//       );
//   }
// }

const styles = StyleSheet.create({
    container: {
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center'
    },

    MainContainer :{
 
        justifyContent: 'center',
        flex:1,
        margin: 10,
        paddingTop: (Platform.OS === 'ios') ? 20 : 0,
         
        },
         
        FlatListItemStyle: {
            padding: 10,
            fontSize: 18,
            height: 44,
          },
});
