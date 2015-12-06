## Clarke & Wright Savings Algorithm
This is a Clarke & Wright Savings algorithm adapted for asymmetric distance (or cost) matrix
and under a simple time window scenario. It was created as part for the IN4704  at the University of Chile.

The scenario is described as N trucks with different weight and volume capacity have to dispatch to M different
clients geographically distributed across town. Those clients can be of 4 types N,M,S and C, each client type has
a time window in which it can be served and a specific service time. The time between different nodes is
calculated considering an average of 30km/h driving speed for the trucks.

The Clarke and Wright algorithm is pretty simple and standard. The truck assignment method is more complex and proceeds as follows:

1.	For each route we create a list of trucks capable of transporting the demand.
2.	If a route has a possible truck that isn’t already in use by another route, it is assigned to it.
3.	If there is no free truck for the route a pseudo-matrix of 1 and 0 is created where the rows are the trucks and the columns are the routes. A 1 is assigned if the route and truck can be matched.
4.	Then we simulate a pivoting process on the columns of the matrix until a strictly positive diagonal is formed, giving a solution for the problem. (This pivoting is done by deleting and recreating rows and columns of the matrix, due to the mathematical restrictions of php. Also a custom Matrix class is created for simplicity).

Notice that no client node can be duplicate. we give an example of how to correct this in data.php.

It was programmed in php for speed and simplicity of writing and reading, not calculating optimality.
The code is not intended to be perfect in any terms, just the simplest of approach to the heuristic proposed for
the problem by Clarke & Wright (1964) and adapted to asymmetric scenario by Paessens (1988).

Programmed by B. Vatter
Model adapted by A Martinez, P. Oyarzun, B. Vatter

use, copy, further develop and hopefully share again :)
