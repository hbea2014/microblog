# microblog

The first assignment is to create a microblog application.

You can use [Silex](http://silex.sensiolabs.org) if you want, but it's not a requirement.

## Objectives

- Display the latest 5 articles on the homepage
- Create an authentication section where someone can
  - login (email + password)
  - register (name + email + password)
  - logout
- Create a page to manage articles (authenticated)
  - create/edit new content (just text for now)
  - list created content
  - delete an article

## Bonus

- Adhere to the [PSR-2](http://www.php-fig.org/psr/psr-2/) coding standards
- Write tests for the authentication and article components

## Method

I used the structure in [the gist demonstrating a model approach](https://gist.github.com/DragonBe/b6f709bd6d1863a07846) that you shared with me back in the days.

## Questions

### Testing

**1. Should I test constructors?**

I've read that it is not necessary because constructors should not do anything, but get dependencies or set properties.

**2. Should I test abstract classes?**

I've read that in general no, but then what about abstract classes that have functions that do important things (which are then inherited by all subclasses)? Seems to me that those should be thoroughly tested actually.
Then if I should test abstract classes? Do you have suggestions about how to test them? I'm thinking about mocking the class and testing the needed methods, what do you think about it?

**3. Should I test getters and setters (sometimes difficult if the properties are private, see OOP question 1)?**

It does not make really sense to me to test those, because things, ie other tests will probably break if there's something going wrong with getters and setters. 

Also most of the time there is almost no logic in getters and setters. And if there is some kind of validation or formatting in them, then it could simply use other methods, which could (even should) be tested themselves anyway.

**4. Should I test function calls inside methods?**

For example, checking that the Model::populate() was called in the constructor with for a non null argument passed at instantiation in Model.php.

### OOP

**1. Should I systematically make all properties private?**

I guess yes, and then change them to protected or public if it becomes needed. But it looks like private properties can create problems with testing (harder to test). Or maybe if it causes problems with testing, then it is a code smell or a sign that there're things to learn for me there...

**2.** 

### Design

**1. Is my directory structure ok?**

 I've seen frameworks using "src" for the folder containing the application code, others using "application", or people using the namespace as folder name (I used that with "Microblog"), what do you think is best / more logical / flexible?

**2. Should the tests be in the "Microblog" folder?**

Any best practice about the name of the test folder (I've seen "test" and "tests")? Its namespacing?
In one sense it looks logical to have the tests in a folder inside the application folder but if there are other namespaces other that Microblog later on (if we introduce other functionalities in other namespaces) then it could be logical to have the tests folder at the same level of "Microblog".

**3.** 

### General questions
**1. In doc blocks, if I have a parameter that is set to null by default but when not null should be an array, then how should I document it?**

I'd go with this `@param null|array $paramName`, what do you think about it?

**2. Why imposing to an abstract function what it should return?**

The abstract populate() function of the abstract Model class informs in the docblocks that it returns $this. It seems strange to me to impose this in the abstract class and function, is it a way to control more the children methods? On the other it's in the docblock so it probably don't have influence on the code itself.

**3. Is it not better to avoid `mixed` in docblock?**

Shouldn't the abstract toArray() function of the abstract Model class return only arrays and therefore have `@return array` in the docblock?

**4. Validation?**

I'm wondering where and how to do validation. It looks like we should validate the data passed to the set methods, checking if data is integer or string or array when required. Those checks are done everywhere so it could make sense to have a validation class that we can use in various classes. 

But if we inject the class in the constructor it might be not so flexible and create a dependency. We could use it as a static helper class but I've read that both helper classes and static class are not so good, design-wise. I was thinking about a validation Service but I don't know how to create a simple service.