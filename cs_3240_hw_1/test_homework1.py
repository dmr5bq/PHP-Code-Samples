
from homework1 import NearestNeighbor


'''
for class NearestNeighbor:
    methods:
        build
        receive_input
        read_data
        clean_data
        run
        sort_data
'''


x = NearestNeighbor()
x.receive_input()
print(str(x.k) + " " + str(x.m) + " " + str(x.file_name))

x.read_data()

# print(x.data)

x.clean_data()

# print (x.data)

x.sort_data()

# print (x.data)

x.run()
