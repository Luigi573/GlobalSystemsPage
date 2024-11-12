let array = [1, -2, 3, -4, 5]
console.log(absoluteValuesArray(array));

function absoluteValuesArray(array){
    let positiveArray = [];

    for(let i = 0; i < array.length; i++){
        if(array[i] >= 0){
            positiveArray.push(array[i]);
        }
    }

    return positiveArray;
}