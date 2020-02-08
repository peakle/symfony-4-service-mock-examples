In this repo I give general examples of how you can mock service methods in symphony 4.

For examples of that look at test/Util dir.

Explanation:
    During migration from symfony 3 to symfony 4,
    i am faced with the problem of mock service methods in tests,
    because in symfony 4 you cannot replace service in container 
    just buy using `set` method of container.
