/**************For adding item to cart********************** */

export const addCart = (product) => {
    return {
        type : "ADDITEM",
        payload : product
    }
}


//For Delete item from Cart

export const delCart = (product) => {
    return {
        type : "DELITEM",
        payload : product
    }
}


export const journal = (product) => {
    return {
        type : "GETJOURNAL",
        payload : product
    }
}