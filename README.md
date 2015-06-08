# Symfony router for Silex

Replace the silex routing system with Symfony router

*Based on the idea of https://github.com/flint/flint*

This is a simple provider which replaces the original Silex routing system with the Symfony router. This enhances performance thanks to the `url_matcher` cache.

This also changes the way routes are defined by using the `yml` format.
