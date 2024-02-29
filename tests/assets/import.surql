CREATE person:beau CONTENT {
    name: "beau",
    age: 25
};

CREATE person:julian CONTENT {
    name: "julian",
    age: 24
};

CREATE person:pebbers CONTENT {
    name: "pebbers",
    age: 18
};

CREATE product:apple CONTENT {
    name: "apple",
    price: 0.5
};

CREATE product:banana CONTENT {
    name: "banana",
    price: 0.3
};

CREATE product:carrot CONTENT {
    name: "carrot",
    price: 0.2
};

RELATE person:beau->likes->product:apple CONTENT {
    rating: 5
};

RELATE person:beau->likes->product:banana CONTENT {
    rating: 4
};

RELATE person:julian->likes->product:banana CONTENT {
    rating: 5
};

RELATE person:julian->likes->product:carrot CONTENT {
    rating: 3
};

RELATE person:pebbers->likes->product:apple CONTENT {
    rating: 3
};

RELATE person:pebbers->likes->product:carrot CONTENT {
    rating: 4
};

-- Create a root user
DEFINE USER beaurt ON ROOT PASSWORD '123456' ROLES OWNER;

-- Create a namespace user
DEFINE USER beauns ON NAMESPACE PASSWORD '123456' ROLES OWNER;

-- Create a database user
DEFINE USER beaudb ON DATABASE PASSWORD '123456' ROLES OWNER;

-- Create a scope user
DEFINE SCOPE user SESSION 1d
    SIGNIN (
        SELECT * FROM user WHERE user = $user AND crypto::argon2::compare(pass, $pass)
    )
    SIGNUP (
        CREATE user CONTENT {
            user: $name,
            pass: crypto::argon2::generate($password)
        }
    );

CREATE user CONTENT {
    user: "beau",
    pass: crypto::argon2::generate("123456")
}