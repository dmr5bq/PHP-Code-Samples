# coding=utf-8

##### inputs
'''
- Value of k: prompt the user for a value of k.
    OK
'''

'''
- Value of M: prompt the user for the value of M, the number of values to be read from the data file. This could \
be smaller than the number of values actually found in the data file.
'''

'''
- Data file name: prompt the user for the name of a data file containing the classified data items. Each item will
be on a line by itself, where each line is the category value (a string) followed by the X and Y values (all
separated by 1 or more spaces). X and Y may be any floating point values (negative, positive or zero).
'''

'''
- Unclassified data values: prompt the user for (X,Y) value pairs. Keep prompting and processing these (see
below) until the user enters values that are 1.0 and 1.0 (yes, this is kind of dumb but it's
simple). Clarification: the user just enters two numbers, without any parentheses or commas.
Example: -3.2 1.7
'''
import copy
from math import sqrt
import heapq
import operator


class NearestNeighbor:

    def __init__(self):
        self.k = None
        self.m = None
        self.file_name = None
        self.data = []

    def build(self):
        self.receive_input()
        self.read_data()
        self.clean_data()

    def receive_input(self):
        self.k = input("Please enter the value of k: \n")
        self.m = input("Please enter the number of entries to be read from the data file: \n")
        self.file_name = input("Enter the name of the data file in the format \"filename.txt\": \n")

    def read_data(self):
        # want to read m values from the file

        data_file = open(self.file_name)

        # put lines in a list structure before they're stripped

        data = []
        for index in range(0, self.m):
            self.data.append(data_file.readline())

    def clean_data(self):
        data_copy = copy.copy(self.data)
        self.data = []

        for line in data_copy:
            if line != '':
                tmp_list = line.split()
                self.data.append(tmp_list)

        # data is now a list of lists of the form [[label, x, y],[...],...]
        for entry in self.data:
            # convert X and Y to floating point
            entry[1] = float(entry[1])
            entry[2] = float(entry[2])

            tmp = (entry[1], entry[2])
            entry[1] = tmp
            entry.pop()

        # data is now a list of the form [[label,(x,y)],[...],...]

    def run(self):
        while 1:
            user_input = raw_input("Please enter an unclassified point: e.g., -1.2 0.5 \n")

            list_input = user_input.split()

            list_input[0] = float(list_input[0])
            list_input[1] = float(list_input[1])

            if list_input[0] == 1.0 and list_input[1] == 1.0:
                break

            # entry : [label,(x,y)]
            # record distance in tuple with distance FIRST and the self.data entry second

            heap = self.build_heap_from_data()
            heap_copy = copy.copy(heap) # used to find the average distance later after the heap is ruined

            nearest_points = []
            category_dict = {}

            # make sure k does not exceed heap size, set equal to the size of the heap if k is larger
            pop_bound = self.k
            if self.k > len(heap):
                pop_bound = len(heap)

            for i in range(0, pop_bound):
                point = heapq.heappop(heap)
                nearest_points.append(point)

            for point in nearest_points:
                dist = point[0]
                entry = point[1]
                category = entry[0]
                xy = entry[1]
                x = xy[0]
                y = xy[1]

                if category_dict.has_key(category):
                    cur_count = category_dict.get(category)
                    category_dict[category] = cur_count + 1
                else:
                    category_dict[category] = 1

                # required output 1
                print (str(category) + '  ' + str(x) + '  ' + str(y) + '  ' + str(dist))

            # find the key with the maximum value
            category_dict_copy = copy.copy(category_dict)
            maximum = max(category_dict_copy.iteritems(), key=operator.itemgetter(1))[0] # !!! DON'T DO THIS W/O COPY
            point_string = '(' + str(list_input[0]) + ', ' + str(list_input[1]) + ')'

            # required output 2
            print ('\n\nData item ' + point_string + ' is assigned category ' + str(maximum) + '\n\n')

            avg_dist_dict = {}
            tmp_sum = 0

            for entry in heap_copy:
                dist = entry[0]
                tmp_sum += float(dist)
                for key in category_dict:
                    avg_dist_dict[key] = tmp_sum / category_dict[key]

            for key in avg_dist_dict:
                # required output 3
                 print ('Average distance to ' + key + ' items is ' + str(avg_dist_dict[key]))

            print ('\n - - - - -  \n \n')

    def sort_data(self):
        self.data.sort(key=lambda entry: (entry[1])[0])

    def build_heap_from_data(self):
        heap = []
        for entry in self.data:
            dist = distance(list_input, entry[1])
            tmp_tuple = (dist, entry)
            # tmp_tuple : ( distance , [ label , ( x, y ) ] )
            heapq.heappush(heap, tmp_tuple)
        return heap
    # End NearestNeighbor class


def distance(point1, point2):
        x1 = point1[0]
        x2 = point2[0]
        y1 = point1[1]
        y2 = point2[1]
        return sqrt((x2-x1)**2 + (y2-y1)**2)
