# Conclusion: Python > PHP, although the implementation is identical.

class TextParser:
	'A Python implementation of the textParser class used for the Daft API.'


	apiKey = 'obvo_not_gonna_have_a_valid_key_here'
	allAreas = ['Malahide', 'Drumcondra', 'Ballymun', 'Santry', 'Whitehall', 'Nubar', 'Shercock', 'Kirkman', 'Conroy']
	userInput = ''
	inputList = []
	searchType = ''
	areas = []
	min_price = 0
	max_price = 0
	bedrooms = 0 # To search for an exact number of bedrooms
	min_bedrooms = 0
	max_bedrooms = 0


	def __splitInput(self, sentence):
		words = sentence.split()
		wordList = []

		for word in words:
			wordList.append(word)

		return wordList


	# Constructor
	def __init__(self, userInput):

		self.userInput = userInput.lower().title()
		self.inputList = self.__splitInput(self.userInput)

		# Would set up API here as well if one was being used.


	def getSearch(self):
		rentList = ['Rent', 'Rental', 'Let', 'Renting'] # Some possible keywords
		buyList = ['Buy', 'Sell', 'Sale', 'Selling']

		for elem in self.inputList:
			if(elem in rentList):
				return 'To Let'
			elif(elem in buyList):
				return 'For Sale'


	# Would normally be fetching a list/array of areas from
	# the API, or have them all cached.
	# For the purpose of this, we have a small sample
	# of areas in a list that the class can recognise.
	def getArea(self):
		for area in self.allAreas:
			if(area in self.inputList):
				return self.allAreas.index(area)


	def getPrice(self):
		prices = []

		for elem in self.inputList:
			if(elem.isdigit() and int(elem) >= 200):
				prices.append(elem)

		if(len(prices) == 1):
			self.min_price = prices[0]
			return int(self.min_price)

		elif(len(prices) == 2):
			self.min_price = min(prices)
			self.max_price = max(prices)

			return int(self.min_price)


	def getMaxPrice(self):
		return int(self.max_price)


	def getBedrooms(self):
		beds = []

		for elem in self.inputList:
			if(elem.isdigit() and int(elem) >= 1 and int(elem) <= 7):
				beds.append(int(elem))

			if(len(beds) == 1):
				self.bedrooms = beds[0]
				return int(self.bedrooms)

			elif(len(beds) == 2):
				self.min_bedrooms = min(beds)
				self.max_bedrooms = max(beds)


	def getMinBeds(self):
		return int(self.min_bedrooms)


	def getMaxBeds(self):
		return int(self.max_bedrooms)




# Testing
test = TextParser("2 bed house to rent in Shercock between 400 and 600 per month")

print(test.inputList)
print()
print("Type of search: " + test.getSearch())
print("Area: " + test.allAreas[test.getArea()])
print("Minimum price: " + str(test.getPrice()) + " / month")
print("Minimum price: " + str(test.getMaxPrice()) + " / month")
print("Number of bedrooms: " + str(test.getBedrooms()))
#print(test.getMinBeds())
#print(test.getMaxBeds())
