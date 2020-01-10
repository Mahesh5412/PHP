import React, { Component } from "react";
import {
    AppRegistry, StyleSheet, FlatList, Text, View, Alert, ActivityIndicator, Platform
} from "react-native";


class ElectronicsScreen extends Component {

    constructor(props)
  {
 
    super(props);
 
    this.state = { 
    isLoading: true
  }
  }

    componentDidMount() {
      
        return fetch('http://10.10.0.2/React-Native/restaurantList.php')
          .then((response) => response.json())
          .then((responseJson) => {
            //alert(JSON.stringify(responseJson));
            this.setState({
              isLoading: false,
              dataSource: responseJson
            }, function() {
              // In this block you can do something with new state.
            });
          })
          .catch((error) => {
            console.error(error);
          });

          
          
      }

  
 FlatListItemSeparator = () => {
     return (
       <View
         style={{
           height: 1,
           width: "100%",
           backgroundColor: "#607D8B",
         }}
       />
     );
   }

          
    render() {

        // if (this.state.isLoading) {
        //     return (
        //       <View style={{flex: 1, paddingTop: 20}}>
        //         <ActivityIndicator />
        //       </View>
        //     );
        //   }
alert(electronics);
        return (
            <View style={styles.container}>
              <View>
            {/* display product details from Products class in ElectronicsScreen */}
                {/* <Products products={electronics} onPress={this.props.addItemToCart} /> */}
            </View>  

            <View style={styles.MainContainer}>
          {/* <Products products={electronics} onPress={this.props.addItemToCart} /> */}

  
       <FlatList
       
          data={ this.state.dataSource }
          
          renderItem={({item}) => 
          //  <Products products={data} onPress={this.props.addItemToCart} />}
          
           <Text style={styles.FlatListItemStyle}> {item.RestaurantName},    {item.area} </Text>}
 
          keyExtractor={(item, index) => index}
          
         />
          {/* <Products products={item.RestaurantName} onPress={this.props.addItemToCart} /> */}
    
    </View>
</View>
        );
    }
}

const mapDispatchToProps = (dispatch) => {
    return {
        addItemToCart: (product) => dispatch({ type: 'ADD_TO_CART', payload: product })
    }
}

export default connect(null, mapDispatchToProps)(ElectronicsScreen);

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