
# Contributing

This file describe rules to keep a consistence in the project :

## Code

This chapter will provide a set of best practices to follow for the evolution of the To do & Co project:

<ol>
<li>For all new routes: 
    <ul>
        <li>Write them without the "action" keyword in the function name</li>
        <li>Access conditions must be verified with voters</li>
    </ul>
    <li>Create unit and functional tests for all new features</li>
    <li>Use a stable version of Symfony</li>
    <li>Deprecated packages should be updated or replaced</li>
    <li>Prefer explicit function, variable, etc. names over comments</li>
    <li>Follow PSR standards (1 & 12) for code styling</li>
</ol>

## Git 
### Commit:
<ol>
<li>If a new route has been created, the commit message should resemble:<br>
API POST resource_x id_resource_x + resource_y + id_resource_x</li>
<ul>
    <li> If it's an API route, begin with "API" as a prefix to the route</li>
    <li> Write the associated HTTP verb</li>
    <li> Write the resource and its id, if using that id</li>
    <li> Write any additional resources following the same pattern as the previous one</li>
</ul>
<li>If an issue has not been resolved at the time of the commit. I add two points to describe the problem with a question mark:</li>

    commit message: my problem?

<li>If the "committed" issue has been resolved:</li>
    
    commit message: my problem: fixed
</ol>

## Test

All PHPUnit tests must be annotated according to certain rules. Each functional test must belong to at least three groups: one for what is expected from the test, one for the action being tested, and one for user contexts. These groups must be added precisely in this order.

### Here is the list of each group that can be added:
<table>
    <tr><th>Expectation</th><th>Action</th><th>Context</th></tr>
    <tr><td>expectRedirection</td><td>list</td><td>unAuth</td></tr>
    <tr><td>expect403</td><td>create</td><td>authUser</td></tr>
    <tr><td>expect404</td><td>toggle</td><td>authAdmin</td></tr>
    <tr><td>expect500</td><td>update</td><td></td></tr>
    <tr><td>expect200</td><td></td><td></td></tr>
</table>




This list is non-exhaustive and subject to change with the ToDo & Co project. However, it is very important to use generic groups to facilitate code clarity.

## Quality code analyze :

Here you have the quality code analyze :
https://codeclimate.com/github/Emile31500/OCR_8/issues




