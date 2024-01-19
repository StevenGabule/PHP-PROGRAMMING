# Implementing class autoloading
When developing PHP using an object-oriented programming (OOP) approach, the
recommendation is to place each class in its own file. The advantage of following this
recommendation is the ease of long-term maintenance and improved readability. The
disadvantage is that each class definition file must be included (that is, using include or its
variants). To address this issue, there is a mechanism built into the PHP language that will
autoload any class that has not already been specifically included.
chap1_autoload_test.php

# Building a deep web scanner
Sometimes you need to scan a website, but go one level deeper. For example, you want to
build a web tree diagram of a website. This can be accomplished by looking for all <A> tags
and following the HREF attributes to the next web page. Once you have acquired the child
pages, you can then continue scanning in order to complete the tree.