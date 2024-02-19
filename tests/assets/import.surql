CREATE person:beau CONTENT {
    name: "beau",
    age: 25
}

CREATE person:julian CONTENT {
    name: "julian",
    age: 24
}

CREATE person:pebbers CONTENT {
    name: "pebbers",
    age: 18
}

CREATE product:apple CONTENT {
    name: "apple",
    price: 0.5
}

CREATE product:banana CONTENT {
    name: "banana",
    price: 0.3
}

CREATE product:carrot CONTENT {
    name: "carrot",
    price: 0.2
}

RELATE person:beau->likes->product:apple CONTENT {
    rating: 5
}

RELATE person:beau->likes->product:banana CONTENT {
    rating: 4
}

RELATE person:julian->likes->product:banana CONTENT {
    rating: 5
}

RELATE person:julian->likes->product:carrot CONTENT {
    rating: 3
}

RELATE person:pebbers->likes->product:apple CONTENT {
    rating: 3
}

RELATE person:pebbers->likes->product:carrot CONTENT {
    rating: 4
}