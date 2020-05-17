## Introduction

This library provides a simple API to display data in a table. It heavily depends on the [Symfony Console component](https://symfony.com/doc/current/components/console.html) to output the data to the console or any other output.

One of the main features is the ability to define the order of the table headers. After that, the order of the row columns is defined by keys. It does not matter in which order you add the fields to the rows, the header row will always define the order.

## Installation

Install the package using `composer require programie/phptable`.